<?php

namespace App\Services;

use App\Models\Member;
use App\Models\AuditLog;

class MembershipService
{
    public function suspend(Member $member, int $actorId): void
    {
        if ($member->membership_status !== 'active') {
            throw new \Exception('Only active members can be suspended.');
        }

        $this->transition($member, 'suspended', $actorId);
    }

    public function terminate(Member $member, int $actorId): void
    {
        if ($member->membership_status === 'terminated') {
            throw new \Exception('Member already terminated.');
        }

        $this->transition($member, 'terminated', $actorId);
    }

    protected function transition(Member $member, string $to, int $actorId): void
    {
        $old = $member->membership_status;

        $member->update([
            'membership_status' => $to,
        ]);

        AuditLog::create([
            'user_id' => $actorId,
            'action' => 'membership_status_changed',
            'model' => Member::class,
            'model_id' => $member->id,
            'old_values' => ['membership_status' => $old],
            'new_values' => ['membership_status' => $to],
        ]);
    }
}
