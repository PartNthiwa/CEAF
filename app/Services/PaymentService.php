<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentCycle;
use App\Models\AuditLog;
use Carbon\Carbon;

class PaymentService
{
    public function markPaid(Payment $payment, int $actorId): void
    {
        if ($payment->status === 'paid') {
            throw new \Exception('Payment already completed.');
        }

        if (! $payment->paymentCycle->isOpen()) {
            throw new \Exception('Cannot pay a closed cycle.');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        AuditLog::create([
            'user_id' => $actorId,
            'action' => 'payment_marked_paid',
            'model' => Payment::class,
            'model_id' => $payment->id,
            'new_values' => ['status' => 'paid'],
        ]);
    }
}
