<?php

namespace App\Http\Controllers\Api;

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
                return $this->errorResponse('You are not family admin');

            }

            $individuals = IndividualProfile::accessibleBy($user->id)
                ->with('careNotes', 'moodChecks')
                ->get();

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
                'individuals' => $individuals,
                'family_users' => $familyUsers,
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Error fetching family members', $e->getMessage());
        }
    }
}
