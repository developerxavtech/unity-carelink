<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StatusController extends BaseController
{
    /**
     * Get the authenticated user's current mood/status.
     *
     * GET /api/status
     */
    public function getCurrentStatus(Request $request)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return $this->sendError('Unauthenticated.', [], 401);
            }

            $data = [
                'activity_status' => $user->activity_status,
                'status_emoji' => $user->status_emoji,
                'status_message' => $user->status_message,
                'status_busy_until' => $user->status_busy_until
                    ? $user->status_busy_until->toIso8601String()
                    : null,
                'full_status' => $user->full_status,
                'is_busy' => $user->isBusy(),
            ];

            return $this->sendResponse($data, 'Current status retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the authenticated user's mood/status.
     *
     * POST /api/status/update
     *
     * Body (all optional):
     *   activity_status  – string, max 255
     *   status_emoji     – string, max 255
     *   status_message   – string, max 1000
     *   status_busy_until – datetime (ISO 8601 / Y-m-d H:i:s), must be in the future
     */
    public function updateStatus(Request $request)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return $this->sendError('Unauthenticated.', [], 401);
            }

            $validator = Validator::make($request->all(), [
                'activity_status' => 'nullable|string|max:255',
                'status_emoji' => 'nullable|string|max:255',
                'status_message' => 'nullable|string|max:1000',
                'status_busy_until' => 'nullable|date|after:now',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $user->update($validator->validated());

            $data = [
                'activity_status' => $user->activity_status,
                'status_emoji' => $user->status_emoji,
                'status_message' => $user->status_message,
                'status_busy_until' => $user->status_busy_until
                    ? $user->status_busy_until->toIso8601String()
                    : null,
                'full_status' => $user->full_status,
                'is_busy' => $user->isBusy(),
            ];

            return $this->sendResponse($data, 'Status updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Clear the authenticated user's mood/status.
     *
     * POST /api/status/clear
     */
    public function clearStatus(Request $request)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return $this->sendError('Unauthenticated.', [], 401);
            }

            $user->update([
                'activity_status' => null,
                'status_emoji' => null,
                'status_message' => null,
                'status_busy_until' => null,
            ]);

            return $this->sendResponse([], 'Status cleared successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }

    // activity status
    public function getActivityStatuses()
    {
        try {

            return $this->sendResponse([
                ['label' => 'Busy at the mall', 'emoji' => '🛍️'],
                ['label' => 'Swimming', 'emoji' => '🏊'],
                ['label' => 'At the gym', 'emoji' => '🏋️'],
                ['label' => 'Driving', 'emoji' => '🚗'],
                ['label' => 'Sleeping', 'emoji' => '😴'],
                ['label' => 'In a meeting', 'emoji' => '🤝'],
                ['label' => 'Out for lunch', 'emoji' => '🍴'],
                ['label' => 'Shift active', 'emoji' => '📋'],
                ['label' => 'On break', 'emoji' => '☕'],
            ], 'Activity statuses retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }
}
