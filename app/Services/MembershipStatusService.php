<?php

namespace App\Services;

use App\Models\Member;
use Carbon\Carbon;

class MembershipStatusService
{
    public static function updateForPayment($payment)
    {
        $member = $payment->member;

        if ($payment->status === 'paid') {
            $member->update(['membership_status' => 'active']);
            return;
        }

        if ($payment->status === 'late') {
            $member->update(['membership_status' => 'late']);
            return;
        }

        if (
            $payment->status === 'pending' &&
            now()->gt($payment->paymentCycle->late_deadline)
        ) {
            $member->update(['membership_status' => 'suspended']);
        }
    }
}