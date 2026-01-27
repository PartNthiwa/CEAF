<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Member;
use App\Models\PaymentCycle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceived;
use App\Models\Payment;


class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING   = 'pending';
    public const STATUS_PAID      = 'paid';
    public const STATUS_LATE      = 'late';
    public const STATUS_DEFAULTED = 'defaulted';


    protected $fillable = [
        'payment_cycle_id',
        'member_id',
        'amount_due',
        'late_fee',
        'paid_at',
        'status',
        'marked_late_at',
        'defaulted_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'marked_late_at' => 'datetime',
        'defaulted_at' => 'datetime',
    ];

   public function paymentCycle()
    {
        return $this->belongsTo(PaymentCycle::class, 'payment_cycle_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }


    public function isLate()
        {
            return $this->status === 'pending' && now()->gt($this->cycle->late_deadline);
        }
   // Apply late fee
    public function applyLateFee($percentage = 0.1)
    {
        if ($this->isLate()) {
            $this->update([
                'status' => 'late',
                'late_fee' => $this->amount_due * $percentage,
            ]);
        }
    }

    // Mark as paid
    public function markPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'late_fee' => 0,
        ]);
    }

    /* Accessor for late fee */

    public function getLateFeeAttribute($value)
    {
        if ($this->status === 'pending' && now()->gt($this->paymentCycle->late_deadline)) {
            return $this->amount_due * 0.10; // 10% late fee
        }

        return $value;
    }
    public function getTotalDueAttribute()
    {
        return $this->amount_due + $this->late_fee;
    }
    
}
