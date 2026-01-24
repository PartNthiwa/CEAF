<?php


namespace App\Livewire\Traits;

use Illuminate\Auth\Access\AuthorizationException;

trait RequiresActiveMembership
{
    public function bootRequiresActiveMembership()
    {
        $member = auth()->user()?->member;

        if (!$member || $member->membership_status !== 'active') {
            throw new AuthorizationException(
                'Your membership is not active. Please settle outstanding payments.'
            );
        }
    }

}