<?php

namespace App\Policies;

use App\Models\IndividualProfile;
use App\Models\User;

class IndividualProfilePolicy
{
    /**
     * Determine if the user can view any individual profiles.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the individual profile.
     */
    public function view(User $user, IndividualProfile $individualProfile): bool
    {
        // Family admin who created the profile
        if ($user->id === $individualProfile->family_user_id) {
            return true;
        }

        // User has role assignment for this individual
        return $user->roleAssignments()
            ->where('individual_profile_id', $individualProfile->id)
            ->exists();
    }

    /**
     * Determine if the user can create individual profiles.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('family_admin');
    }

    /**
     * Determine if the user can update the individual profile.
     */
    public function update(User $user, IndividualProfile $individualProfile): bool
    {
        // Only the family admin who created it can update
        return $user->id === $individualProfile->family_user_id;
    }

    /**
     * Determine if the user can delete the individual profile.
     */
    public function delete(User $user, IndividualProfile $individualProfile): bool
    {
        // Only the family admin who created it can delete
        return $user->id === $individualProfile->family_user_id;
    }
}
