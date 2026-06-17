<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\WaitlistWelcomeMail;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WaitlistRegistrationController extends Controller
{
    public function __construct(protected TwilioService $twilio)
    {
    }

    /**
     * Handle a join-waitlist submission for either a family or a DSP account,
     * create the real portal account, text the user a temporary password,
     * and report back where to redirect.
     */
    public function store(Request $request)
    {
        $role = $request->input('role');

        if (!in_array($role, ['family', 'dsp'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid role'], 422);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $tempPassword = Str::password(12);

        try {
            $user = DB::transaction(function () use ($data, $role, $request, $tempPassword) {
                [$firstName, $lastName] = $this->splitName($data['full_name']);

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $data['email'],
                    'password' => Hash::make($tempPassword),
                    'phone' => $data['phone'],
                    'status' => 'active',
                ]);

                $user->assignRole($role === 'family' ? 'family_admin' : 'dsp');

                if ($role === 'family') {
                    $this->storeFamilyDetails($user, $request);
                } else {
                    $this->storeDspDetails($user, $request);
                }

                return $user;
            });
        } catch (\Throwable $e) {
            Log::error('Join-waitlist registration failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while saving your registration. Please try again.',
            ], 500);
        }

        $this->twilio->sendSms(
            $user->phone,
            "Welcome to Unity CareLink! Your temporary password is: {$tempPassword}. Log in and change it from your profile settings."
        );

        try {
            Mail::to($user->email)->send(new WaitlistWelcomeMail($user, $tempPassword));
        } catch (\Throwable $e) {
            Log::error('Join-waitlist welcome email failed: ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'status' => 'success',
            'message' => $role === 'family' ? 'Family registration saved' : 'DSP registration saved',
            'redirect' => route('dashboard'),
        ]);
    }

    private function storeFamilyDetails(User $user, Request $request): void
    {
        $careProfile = $request->input('care_profile', []);
        $pilotQuestions = $request->input('pilot_questions', []);
        $feedback = $request->input('feedback', []);

        [$city, $zip] = $this->splitCityZip($request->input('city_zip', ''));

        $user->familyWaitlistDetail()->create([
            'city' => $city,
            'zip_code' => $zip,
            'communication_method' => $request->input('comm_method', 'Email'),
            'care_profiles' => [
                'loved_one_name' => $careProfile['loved_one_name'] ?? $request->input('loved_one_name', ''),
                'age_range' => $careProfile['age_range'] ?? $request->input('age_range', ''),
                'mobility' => $careProfile['mobility'] ?? $request->input('mobility', []),
                'mobility_other' => $careProfile['mobility_other'] ?? $request->input('mobility_other', ''),
                'behavioral' => $careProfile['behavioral'] ?? $request->input('behavioral', ''),
                'pickup_windows' => $careProfile['pickup_windows'] ?? $request->input('pickup_windows', ''),
            ],
            'pilot_questions' => [
                'trips' => $pilotQuestions['trips'] ?? $request->input('trips', []),
                'distance' => $pilotQuestions['distance'] ?? $request->input('distance', ''),
                'frequency' => $pilotQuestions['frequency'] ?? $request->input('frequency', ''),
                'collaboration' => $pilotQuestions['collaboration'] ?? $request->input('collaboration', ''),
                'trusted_families' => $pilotQuestions['trusted_families'] ?? $request->input('trusted_families', ''),
            ],
            'feedback_transportation' => $feedback['transportation'] ?? $request->input('transportation_feedback', ''),
            'feedback_wants' => $feedback['platform_wants'] ?? $request->input('platform_wants', ''),
            'pilot_acknowledged' => $request->boolean('pilot_acknowledged'),
        ]);
    }

    private function storeDspDetails(User $user, Request $request): void
    {
        $availability = $request->input('availability', []);
        $pilotQuestions = $request->input('pilot_questions', []);

        $comfortDisabilities = $request->input('comfort_disabilities');
        $comfortValue = $comfortDisabilities === 'Depends'
            ? $request->input('comfort_explain', '')
            : ($comfortDisabilities ?? '');

        $user->dspWaitlistDetail()->create([
            'city_service_area' => $request->input('city_service_area', ''),
            'has_drivers_license' => $request->boolean('has_drivers_license') || $request->input('drivers_license') === 'Yes',
            'has_auto_insurance' => $request->boolean('has_auto_insurance') || $request->input('auto_insurance') === 'Yes',
            'has_reliable_vehicle' => $request->boolean('has_reliable_vehicle') || $request->input('reliable_vehicle') === 'Yes',
            'comfort_transporting_disabilities' => $comfortValue,
            'availability' => [
                'days' => $availability['days'] ?? $request->input('days', []),
                'times' => $availability['time_windows'] ?? $request->input('time_windows', []),
            ],
            'max_distance' => $request->input('max_distance', ''),
            'comfort_levels' => $request->input('comfort_levels', $request->input('comfort_level', [])),
            'experience' => $request->input('dsp_experience', ''),
            'certifications' => $request->input('certifications', ''),
            'message_to_families' => $request->input('about_you', ''),
            'pilot_questions' => [
                'longterm_value' => $pilotQuestions['longterm_value'] ?? $request->input('longterm_value', ''),
                'concerns' => $pilotQuestions['concerns'] ?? $request->input('concerns', ''),
            ],
            'pilot_acknowledged' => $request->boolean('pilot_acknowledged'),
        ]);
    }

    /**
     * Split "John Smith" into ['John', 'Smith']. Single-word names keep the
     * last name empty since the users table stores first/last separately.
     */
    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    /**
     * Mirrors the legacy api.php city/zip parsing: "City / 12345" or
     * free text containing a 5-digit zip embedded in it.
     */
    private function splitCityZip(string $cityZip): array
    {
        $city = $cityZip;
        $zip = '';

        if (str_contains($cityZip, '/')) {
            [$city, $zip] = array_map('trim', explode('/', $cityZip, 2));
        } elseif (preg_match('/\d{5}/', $cityZip, $matches)) {
            $zip = $matches[0];
            $city = trim(str_replace($zip, '', $cityZip), " ,");
        }

        return [$city, $zip];
    }
}
