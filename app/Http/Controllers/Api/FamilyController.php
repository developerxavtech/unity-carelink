<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CareNoteResource;
use App\Models\CareNote;
use App\Models\IndividualProfile;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class FamilyController extends BaseController
{
    public function members(Request $request)
    {
        try {
            $user = Auth::user();
            $familyUsers = collect();

            // Family admins and members see their family's individuals
            if (! $user->hasRole('family_admin')) {
                return $this->sendError('You are not family admin');

            }

            // $individuals = IndividualProfile::accessibleBy($user->id)
            //     ->with('careNotes', 'moodChecks')
            //     ->get();

            // If family admin, also get their linked family members (users)
            if ($user->hasRole('family_admin')) {
                $familyUsers = $user->familyMembers;
            } elseif ($user->hasRole('family_member')) {
                // If family member, get the admin and other members
                $familyUsers = User::where('id', $user->family_admin_id)
                    ->orWhere('family_admin_id', $user->family_admin_id)
                    ->get()
                    ->reject(fn ($u) => $u->id === $user->id);
            }

            return $this->sendResponse('Family members', [
                'family_users' => $familyUsers
            ]);
        } catch (\Exception $e) {
            return $this->sendError('Error fetching family members', $e->getMessage());
        }
    }

    public function getDspDetails(Request $request, $id)
    {
        try {
            $dsp = User::role('dsp')->with('dspProfile')->findOrFail($id);

            return $this->sendResponse('DSP Details', $dsp);
        } catch (\Exception $e) {
            return $this->sendError('Error fetching DSP details', $e->getMessage());
        }
    }

    public function assignedDsps(Request $request)
    {
        try {
            $user = Auth::user();

            // Get conversation IDs where the current user is a participant
            $conversationIds = $user->conversations()->pluck('conversations.id');

            // Find DSPs who share these conversations
            $dsps = User::role('dsp')
                ->whereHas('conversations', function ($query) use ($conversationIds) {
                    $query->whereIn('conversations.id', $conversationIds);
                })
                ->with('dspProfile')
                ->get();

            return $this->sendResponse('Assigned DSPs', $dsps);
        } catch (\Exception $e) {
            return $this->sendError('Error fetching assigned DSPs', $e->getMessage());
        }
    }

    public function dspList(Request $request)
    {
        try {
            $dsps = User::role('dsp')->with('dspProfile')->get();

            return $this->sendResponse('DSP List', $dsps);
        } catch (\Exception $e) {
            return $this->sendError('Error fetching DSP list', $e->getMessage());
        }
    }

    /**
     * Daily logs DSPs have filed against this family admin.
     *
     * GET /api/family/daily-logs
     */
    public function dailyLogs(Request $request)
    {
        try {
            $user = Auth::user();

            if (! $user->hasRole('family_admin')) {
                return $this->sendError('You are not a family admin.', [], 403);
            }

            $logs = CareNote::where('family_user_id', $user->id)
                ->with('dsp')
                ->orderBy('shift_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

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
            return $this->sendError('Error fetching daily logs', $e->getMessage());
        }
    }
}
