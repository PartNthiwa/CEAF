<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Event extends Model
{
    protected $fillable = [
        'member_id',
        'person_id',
        'status',
        'approved_at',
        'approved_amount',
        'paid_from_seed',
        'requires_replenishment',
    ];

    protected $casts = [
        'approved_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EventDocument::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function details()
    {
        return $this->hasOne(EventDetail::class);
    }

    public function coverImage()
    {
        return $this->hasOne(EventDocument::class)
            ->where('type', 'image');
    }

    public function paymentCycle()
    {
        return $this->hasOne(PaymentCycle::class,'id');
    }
}
