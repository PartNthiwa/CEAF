<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
USE Illuminate\Queue\SerializesModels;
use App\Models\Payment;

class EnforceLatePayments implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
   public function handle()
    {
        $latePayments = Payment::where('status', 'late')
            ->whereHas('paymentCycle', function ($q) {
                $q->whereDate('late_deadline', '<', now());
            })
            ->with('member')
            ->get();

        foreach ($latePayments as $payment) {
            if ($payment->member->membership_status === 'active') {
                $payment->member->update([
                    'membership_status' => 'suspended',
                ]);
            }
        }
    }
}
