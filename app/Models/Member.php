<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'membership_status',
        'join_date',
        'status_changed_at',
        'member_number',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'join_date' => 'date',
        'status_changed_at' => 'datetime',
          'approved_at' => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class);
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    // ─── Guards / Meaning ──────────────────────────

    public function isActive(): bool
    {
        return $this->membership_status === 'active';
    }

    public function canSubmitEvents(): bool
    {
        return in_array($this->membership_status, ['active', 'late']);
    }
}
