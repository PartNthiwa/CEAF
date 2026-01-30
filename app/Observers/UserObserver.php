<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Member;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Member::create([
            'user_id' => $user->id,
            'membership_status' => 'pending',
            'join_date' => now(),
            'approved_at' => null,
            'approved_by' => null,
            'member_number' => null,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
