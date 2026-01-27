<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Services\MembershipStatusService;
use Carbon\Carbon;

class EnforceLatePayments extends Command
{
    protected $signature = 'enforce:late-payments';
    protected $description = 'Mark overdue payments as late, suspend members if unpaid, and terminate after grace period.';

    // Number of days a member can remain suspended before termination
    const GRACE_PERIOD_DAYS = 30;

   public function handle()
    {
        $now = now();

        $this->info('Enforcing payment rules...');

        $payments = Payment::with(['paymentCycle', 'member'])
            ->whereIn('status', ['pending', 'late'])
            ->get();

        foreach ($payments as $payment) {
            $cycle  = $payment->paymentCycle;
            $member = $payment->member;

            //  Mark LATE (after due_date)
            if (
                $payment->status === 'pending' &&
                $now->gt($cycle->due_date)
            ) {
                $payment->update([
                    'status' => 'late',
                    'late_fee' => $payment->amount_due * 0.10,
                    'marked_late_at' => $now,
                ]);
            }

            // Mark DEFAULTED (after late_deadline)
            if (
                in_array($payment->status, ['pending', 'late']) &&
                $now->gt($cycle->late_deadline)
            ) {
                $payment->update([
                    'status' => 'defaulted',
                    'defaulted_at' => $now,
                ]);
            }

            //  Sync membership
            MembershipStatusService::sync($member);

            // Terminate after grace period
            if ($member->membership_status === 'suspended') {
                $suspendedSince = $member->status_changed_at ?? $member->updated_at;

                if ($now->diffInDays($suspendedSince) >= self::GRACE_PERIOD_DAYS) {
                    $member->update([
                        'membership_status' => 'terminated',
                        'status_changed_at' => $now,
                    ]);
                }
            }
        }

        $this->info('Payment enforcement complete.');
    }
}
