<?php

namespace App\Http\Controllers\Api;

use App\Events\RideRequested;
use App\Events\RideResponded;
use App\Events\RideStatusChanged;
use App\Models\DspProfile;
use App\Models\Ride;
use App\Models\User;
use App\Services\FirebasePushService;
use App\Services\GeoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RideController extends BaseController
{
    public function __construct(private FirebasePushService $push)
    {
        // Ride booking is a family-side action; responding to/running a ride
        // is a DSP-side action. Registered as deferred controller middleware
        // (rather than checked directly here) so it only runs against real
        // HTTP requests — Artisan commands like `route:list` instantiate
        // controllers with no authenticated user.
        $this->middleware(function ($request, $next) {
            if (! Auth::user()?->hasAnyRole(['family_admin', 'family_member', 'dsp'])) {
                abort(403, 'Access denied.');
            }

            return $next($request);
        });
    }

    /**
     * List the vehicle types available for booking, with their fare rates.
     *
     * GET /api/rides/vehicle-types
     */
    public function vehicleTypes()
    {
        try {
            $types = collect(config('rides.vehicle_types'))
                ->map(fn ($rates, $key) => [
                    'key' => $key,
                    'label' => $rates['label'],
                    'base_fare' => $rates['base_fare'],
                    'per_mile' => $rates['per_mile'],
                ])
                ->values();

            return $this->sendResponse($types, 'Vehicle types retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Vehicle types could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Find available DSPs near a pickup point, with fare/ETA, for a given
     * destination and vehicle type. Fare is the same across all DSPs
     * returned (it's driven by the pickup->destination trip distance, not
     * a DSP's distance to the pickup) — each DSP's own distance/ETA is only
     * used to help the family choose who to book.
     *
     * POST /api/rides/search-dsps
     */
    public function searchDsps(Request $request)
    {
        try {
            $this->authorizeFamily();

            $validated = $request->validate([
                'pickup_latitude' => 'required|numeric|between:-90,90',
                'pickup_longitude' => 'required|numeric|between:-180,180',
                'destination_address' => 'required|string|max:255',
                'destination_latitude' => 'required|numeric|between:-90,90',
                'destination_longitude' => 'required|numeric|between:-180,180',
                'vehicle_type' => ['required', Rule::in(array_keys(config('rides.vehicle_types')))],
            ]);

            $tripDistance = GeoService::distanceInMiles(
                $validated['pickup_latitude'],
                $validated['pickup_longitude'],
                $validated['destination_latitude'],
                $validated['destination_longitude'],
            );

            $fare = $this->calculateFare($validated['vehicle_type'], $tripDistance);

            $radius = config('rides.search_radius_miles');

            $dsps = DspProfile::availableWithFreshLocation()
                ->with('user')
                ->get()
                ->map(function (DspProfile $profile) use ($validated) {
                    $distance = GeoService::distanceInMiles(
                        $validated['pickup_latitude'],
                        $validated['pickup_longitude'],
                        (float) $profile->latitude,
                        (float) $profile->longitude,
                    );

                    return [
                        'profile' => $profile,
                        'distance_to_pickup_miles' => $distance,
                    ];
                })
                ->filter(fn ($entry) => $entry['distance_to_pickup_miles'] <= $radius)
                ->sortBy('distance_to_pickup_miles')
                ->values()
                ->map(fn ($entry) => [
                    'dsp_user_id' => $entry['profile']->user->id,
                    'name' => trim($entry['profile']->user->first_name.' '.$entry['profile']->user->last_name),
                    'profile_photo' => $entry['profile']->user->profile_photo,
                    'distance_to_pickup_miles' => $entry['distance_to_pickup_miles'],
                    'eta_minutes' => GeoService::etaMinutes($entry['distance_to_pickup_miles']),
                ]);

            return $this->sendResponse([
                'vehicle_type' => $validated['vehicle_type'],
                'destination_address' => $validated['destination_address'],
                'trip_distance_miles' => $tripDistance,
                'fare' => $fare,
                'dsps' => $dsps,
            ], 'Nearby DSPs retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('DSPs could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Book a specific DSP from the search results.
     *
     * POST /api/rides
     */
    public function store(Request $request)
    {
        try {
            $this->authorizeFamily();

            $validated = $request->validate([
                'dsp_user_id' => 'required|integer|exists:users,id',
                'individual_profile_id' => 'nullable|integer|exists:individual_profiles,id',
                'vehicle_type' => ['required', Rule::in(array_keys(config('rides.vehicle_types')))],
                'pickup_address' => 'required|string|max:255',
                'pickup_latitude' => 'required|numeric|between:-90,90',
                'pickup_longitude' => 'required|numeric|between:-180,180',
                'destination_address' => 'required|string|max:255',
                'destination_latitude' => 'required|numeric|between:-90,90',
                'destination_longitude' => 'required|numeric|between:-180,180',
            ]);

            $dsp = User::find($validated['dsp_user_id']);

            if (! $dsp->hasRole('dsp')) {
                return $this->sendError('dsp_user_id must belong to a DSP.', [], 422);
            }

            if (! $dsp->dspProfile || ! $dsp->dspProfile->is_available) {
                return $this->sendError('This DSP is not currently available.', [], 422);
            }

            $distance = GeoService::distanceInMiles(
                $validated['pickup_latitude'],
                $validated['pickup_longitude'],
                $validated['destination_latitude'],
                $validated['destination_longitude'],
            );

            $ride = Ride::create([
                'family_user_id' => Auth::id(),
                'dsp_user_id' => $dsp->id,
                'individual_profile_id' => $validated['individual_profile_id'] ?? null,
                'vehicle_type' => $validated['vehicle_type'],
                'status' => Ride::STATUS_PENDING,
                'pickup_address' => $validated['pickup_address'],
                'pickup_latitude' => $validated['pickup_latitude'],
                'pickup_longitude' => $validated['pickup_longitude'],
                'destination_address' => $validated['destination_address'],
                'destination_latitude' => $validated['destination_latitude'],
                'destination_longitude' => $validated['destination_longitude'],
                'distance_miles' => $distance,
                'fare' => $this->calculateFare($validated['vehicle_type'], $distance),
            ]);

            event(new RideRequested($ride));

            $this->push->sendToUser(
                $dsp,
                'New Ride Request',
                'A family has requested a ride to '.$ride->destination_address,
                ['ride_id' => $ride->id, 'type' => 'ride.requested']
            );

            return $this->sendResponse($this->formatRide($ride->load(['familyAdmin', 'dsp'])), 'Ride requested successfully.', 201);
        } catch (Exception $e) {
            return $this->sendError('Ride could not be requested.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * The DSP accepts or rejects a pending ride request.
     *
     * POST /api/rides/{ride}/respond
     */
    public function respond(Request $request, Ride $ride)
    {
        try {
            if (Auth::id() !== $ride->dsp_user_id) {
                return $this->sendError('This ride was not requested from you.', [], 403);
            }

            if ($ride->status !== Ride::STATUS_PENDING) {
                return $this->sendError('This ride is no longer pending.', [], 422);
            }

            $validated = $request->validate([
                'decision' => 'required|in:accept,reject',
                'reason' => 'nullable|string|max:255',
            ]);

            $accepted = $validated['decision'] === 'accept';

            $ride->update([
                'status' => $accepted ? Ride::STATUS_ACCEPTED : Ride::STATUS_REJECTED,
                'responded_at' => now(),
                'rejection_reason' => $accepted ? null : ($validated['reason'] ?? null),
            ]);

            event(new RideResponded($ride));

            $this->push->sendToUser(
                $ride->familyAdmin,
                $accepted ? 'Ride Accepted' : 'Ride Declined',
                $accepted
                    ? 'Your DSP accepted the ride request.'
                    : 'Your DSP declined the ride request. Please try booking another DSP.',
                ['ride_id' => $ride->id, 'type' => 'ride.responded']
            );

            return $this->sendResponse($this->formatRide($ride->load(['familyAdmin', 'dsp'])), 'Response recorded successfully.');
        } catch (Exception $e) {
            return $this->sendError('Response could not be recorded.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * The DSP starts an accepted ride.
     *
     * POST /api/rides/{ride}/start
     */
    public function start(Ride $ride)
    {
        try {
            if (Auth::id() !== $ride->dsp_user_id) {
                return $this->sendError('This ride was not requested from you.', [], 403);
            }

            if ($ride->status !== Ride::STATUS_ACCEPTED) {
                return $this->sendError('Only an accepted ride can be started.', [], 422);
            }

            $ride->update(['status' => Ride::STATUS_IN_PROGRESS, 'started_at' => now()]);

            event(new RideStatusChanged($ride));

            return $this->sendResponse($this->formatRide($ride->load(['familyAdmin', 'dsp'])), 'Ride started successfully.');
        } catch (Exception $e) {
            return $this->sendError('Ride could not be started.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * The DSP completes an in-progress ride.
     *
     * POST /api/rides/{ride}/complete
     */
    public function complete(Ride $ride)
    {
        try {
            if (Auth::id() !== $ride->dsp_user_id) {
                return $this->sendError('This ride was not requested from you.', [], 403);
            }

            if ($ride->status !== Ride::STATUS_IN_PROGRESS) {
                return $this->sendError('Only an in-progress ride can be completed.', [], 422);
            }

            $ride->update(['status' => Ride::STATUS_COMPLETED, 'ended_at' => now()]);

            event(new RideStatusChanged($ride));

            return $this->sendResponse($this->formatRide($ride->load(['familyAdmin', 'dsp'])), 'Ride completed successfully.');
        } catch (Exception $e) {
            return $this->sendError('Ride could not be completed.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Either party cancels a ride before it's underway.
     *
     * POST /api/rides/{ride}/cancel
     */
    public function cancel(Ride $ride)
    {
        try {
            $userId = Auth::id();

            if ($userId !== $ride->family_user_id && $userId !== $ride->dsp_user_id) {
                return $this->sendError('You are not part of this ride.', [], 403);
            }

            if (! in_array($ride->status, [Ride::STATUS_PENDING, Ride::STATUS_ACCEPTED], true)) {
                return $this->sendError('This ride can no longer be cancelled.', [], 422);
            }

            $ride->update(['status' => Ride::STATUS_CANCELLED]);

            event(new RideStatusChanged($ride));

            $otherParty = $userId === $ride->family_user_id ? $ride->dsp : $ride->familyAdmin;

            $this->push->sendToUser(
                $otherParty,
                'Ride Cancelled',
                'The ride has been cancelled.',
                ['ride_id' => $ride->id, 'type' => 'ride.cancelled']
            );

            return $this->sendResponse($this->formatRide($ride->load(['familyAdmin', 'dsp'])), 'Ride cancelled successfully.');
        } catch (Exception $e) {
            return $this->sendError('Ride could not be cancelled.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Ride history for the authenticated user — family admins/members see
     * rides they booked, DSPs see rides booked with them.
     *
     * GET /api/rides
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $query = $user->hasRole('dsp')
                ? Ride::forDsp($user->id)
                : Ride::forFamily($user->id);

            $query->with(['familyAdmin', 'dsp']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $rides = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));

            $rides->getCollection()->transform(fn ($ride) => $this->formatRide($ride));

            return $this->sendResponse($rides, 'Rides retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Rides could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/rides/{ride}
     */
    public function show(Ride $ride)
    {
        try {
            $userId = Auth::id();

            if ($userId !== $ride->family_user_id && $userId !== $ride->dsp_user_id) {
                return $this->sendError('You are not part of this ride.', [], 403);
            }

            return $this->sendResponse($this->formatRide($ride->load(['familyAdmin', 'dsp'])), 'Ride retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Ride could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    private function authorizeFamily(): void
    {
        abort_unless(Auth::user()->hasAnyRole(['family_admin', 'family_member']), 403, 'Access denied. Family role required.');
    }

    private function calculateFare(string $vehicleType, float $distanceMiles): float
    {
        $rates = config("rides.vehicle_types.{$vehicleType}");

        return round($rates['base_fare'] + ($distanceMiles * $rates['per_mile']), 2);
    }

    private function formatRide(Ride $ride): array
    {
        return [
            'id' => $ride->id,
            'status' => $ride->status,
            'vehicle_type' => $ride->vehicle_type,
            'family' => [
                'id' => $ride->familyAdmin->id,
                'name' => trim($ride->familyAdmin->first_name.' '.$ride->familyAdmin->last_name),
            ],
            'dsp' => [
                'id' => $ride->dsp->id,
                'name' => trim($ride->dsp->first_name.' '.$ride->dsp->last_name),
            ],
            'individual_profile_id' => $ride->individual_profile_id,
            'pickup_address' => $ride->pickup_address,
            'pickup_latitude' => (float) $ride->pickup_latitude,
            'pickup_longitude' => (float) $ride->pickup_longitude,
            'pickup_lat' => (float) $ride->pickup_latitude,
            'pickup_lng' => (float) $ride->pickup_longitude,
            'destination_address' => $ride->destination_address,
            'destination_latitude' => (float) $ride->destination_latitude,
            'destination_longitude' => (float) $ride->destination_longitude,
            'destination_lat' => (float) $ride->destination_latitude,
            'destination_lng' => (float) $ride->destination_longitude,
            'distance_miles' => (float) $ride->distance_miles,
            'fare' => (float) $ride->fare,
            'rejection_reason' => $ride->rejection_reason,
            'requested_at' => $ride->created_at->toIso8601String(),
            'responded_at' => $ride->responded_at?->toIso8601String(),
            'started_at' => $ride->started_at?->toIso8601String(),
            'ended_at' => $ride->ended_at?->toIso8601String(),
        ];
    }
}
