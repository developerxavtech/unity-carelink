<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CareNoteResource;
use App\Models\CareNote;
use App\Models\IndividualProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DailyLogController extends BaseController
{
    /**
     * Ensure the authenticated user is a DSP or program staff member.
     */
    protected function authorizeDsp(): void
    {
        abort_unless(Auth::user()->hasAnyRole(['dsp', 'program_staff']), 403, 'Access denied. DSP role required.');
    }

    /**
     * Display a listing of the authenticated DSP's daily logs.
     *
     * GET /api/dsp/daily-logs
     */
    public function index(Request $request)
    {
        try {
            $this->authorizeDsp();

            $query = CareNote::where('dsp_user_id', Auth::id())
                ->with('individualProfile');

            if ($request->filled('individual_id')) {
                $query->where('individual_profile_id', $request->individual_id);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('shift_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('shift_date', '<=', $request->end_date);
            }

            $perPage = $request->get('per_page', 15);

            $logs = $query->orderBy('shift_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return $this->sendResponse([
                'logs' => CareNoteResource::collection($logs->items()),
                'pagination' => [
                    'total' => $logs->total(),
                    'per_page' => $logs->perPage(),
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                ],
            ], 'Daily logs retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Daily logs could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created daily log.
     *
     * POST /api/dsp/daily-logs
     */
    public function store(Request $request)
    {
        try {
            $this->authorizeDsp();

            $validator = Validator::make($request->all(), [
                'individual_profile_id' => 'required|integer|exists:individual_profiles,id',
                'shift_date' => 'nullable|date',
                'notes' => 'required|string',
                'mood' => 'nullable|string|in:great,good,okay,concern',
                'activities' => 'nullable|string',
                'meals' => 'nullable|string',
                'medications' => 'nullable|string',
                'incidents' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $validated = $validator->validated();

            $accessible = IndividualProfile::accessibleBy(Auth::id())
                ->where('id', $validated['individual_profile_id'])
                ->exists();

            if (! $accessible) {
                return $this->sendError('You do not have access to this individual profile.', [], 403);
            }

            $log = CareNote::create([
                ...$validated,
                'dsp_user_id' => Auth::id(),
                'shift_date' => $validated['shift_date'] ?? now()->toDateString(),
            ]);

            return $this->sendResponse(new CareNoteResource($log->load('individualProfile')), 'Daily log created successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Daily log could not be created.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified daily log.
     *
     * GET /api/dsp/daily-logs/{id}
     */
    public function show(string $id)
    {
        try {
            $this->authorizeDsp();

            $log = CareNote::where('dsp_user_id', Auth::id())
                ->with('individualProfile')
                ->findOrFail($id);

            return $this->sendResponse(new CareNoteResource($log), 'Daily log retrieved successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Daily log not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Daily log could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified daily log.
     *
     * PUT/PATCH /api/dsp/daily-logs/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->authorizeDsp();

            $log = CareNote::where('dsp_user_id', Auth::id())->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'shift_date' => 'sometimes|required|date',
                'notes' => 'sometimes|required|string',
                'mood' => 'nullable|string|in:great,good,okay,concern',
                'activities' => 'nullable|string',
                'meals' => 'nullable|string',
                'medications' => 'nullable|string',
                'incidents' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $log->update($validator->validated());

            return $this->sendResponse(new CareNoteResource($log->load('individualProfile')), 'Daily log updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Daily log not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Daily log could not be updated.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified daily log.
     *
     * DELETE /api/dsp/daily-logs/{id}
     */
    public function destroy(string $id)
    {
        try {
            $this->authorizeDsp();

            $log = CareNote::where('dsp_user_id', Auth::id())->findOrFail($id);
            $log->delete();

            return $this->sendResponse([], 'Daily log deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Daily log not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Daily log could not be deleted.', ['error' => $e->getMessage()], 500);
        }
    }
}
