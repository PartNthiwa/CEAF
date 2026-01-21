<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentCycle extends Model
{
    protected $fillable = [
        'type',
        'year',
        'amount_per_member',
        'start_date',
        'due_date',
        'late_deadline',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'late_deadline' => 'date',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }


    public function isLateWindowPassed(): bool
    {
        return now()->greaterThan(\Carbon\Carbon::parse($this->late_deadline));
    }

}
