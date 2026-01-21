<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dependent extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'relationship',
        'profile_completed',
        'status',
    ];

    // ─── Relationships ─────────────────────────────

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // ─── Guards ────────────────────────────────────

    protected static function booted()
    {
        static::updating(function ($dependent) {
            if ($dependent->getOriginal('status') === 'deceased') {
                throw new \Exception('Deceased dependents cannot be modified.');
            }
        });
    }

    public function isDeceased(): bool
    {
        return $this->status === 'deceased';
    }

    public function documents()
    {
        return $this->hasMany(DependentDocument::class);
    }

}
