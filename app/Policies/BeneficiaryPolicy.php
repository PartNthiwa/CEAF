<?php

namespace App\Policies;

use App\Models\User;

class BeneficiaryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function requestChange(User $user, Member $member): bool
    {
        return $member->user_id === $user->id;
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }
}
