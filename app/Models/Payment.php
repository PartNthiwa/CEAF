<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'payment_cycle_id',
        'member_id',
        'amount_due',
        'late_fee',
        'paid_at',
        'status',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function paymentCycle(): BelongsTo
    {
        return $this->belongsTo(PaymentCycle::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function calculateAmountDue(): int
    {
        $amount = $this->amount_due;

        $lateFeeType = $this->paymentCycle->late_fee_type ?? 'flat';
        $lateFeeValue = $this->paymentCycle->late_fee_value ?? 0;

        if ($this->status === 'pending' && now()->greaterThan($this->paymentCycle->due_date)) {
            if ($lateFeeType === 'flat') {
                $amount += $lateFeeValue;
            } elseif ($lateFeeType === 'percentage') {
                $amount += ceil($amount * ($lateFeeValue / 100));
            }
        }

        return $amount;
    }

}
