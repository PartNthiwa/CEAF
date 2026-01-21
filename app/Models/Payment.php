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
}
