<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Services\MembershipStatusService;

class EnforceLatePayments extends Command
{
    protected $signature = 'enforce:late-payments';
    protected $description = 'Mark overdue payments as late and suspend members if unpaid.';

    public function handle()
    {
        $payments = Payment::with(['paymentCycle', 'member'])
            ->where('status', 'pending')
            ->get();

        foreach ($payments as $payment) {
            if (now()->gt($payment->paymentCycle->late_deadline)) {
                $payment->update([
                    'status' => 'late',
                    'late_fee' => $payment->amount_due * 0.10,
                ]);

                MembershipStatusService::updateForPayment($payment);
            }
        }

        $this->info('Late payments enforced successfully.');
    }
}