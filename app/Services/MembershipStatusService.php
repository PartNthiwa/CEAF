<?php

namespace App\Services;

use App\Models\Member;
use Carbon\Carbon;

class MembershipStatusService
{
    public static function sync(Member $member): void
    {
        $hasDefaulted = $member->payments()
            ->where('status', 'defaulted')
            ->exists();

        $hasLate = $member->payments()
            ->where('status', 'late')
            ->exists();

        if ($hasDefaulted) {
            self::setStatus($member, 'suspended');
            return;
        }

        if ($hasLate) {
            self::setStatus($member, 'late');
            return;
        }

        self::setStatus($member, 'active');
    }

    protected static function setStatus(Member $member, string $status): void
    {
        if ($member->membership_status !== $status) {
            $member->update([
                'membership_status' => $status,
                'status_changed_at' => now(),
            ]);
        }
    }
}