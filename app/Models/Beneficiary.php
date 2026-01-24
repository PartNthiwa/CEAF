<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beneficiary extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'contact',
        'percentage',
    ];

    // ─── Relationships ─────────────────────────────
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
    // ─── Guards ────────────────────────────────────

    protected static function booted()
    {
        static::creating(function ($beneficiary) {
            if ($beneficiary->person_id) return;

            $parts = preg_split('/\s+/', trim($beneficiary->name), 2);

            $person = Person::firstOrCreate(
                [
                    'member_id'  => $beneficiary->member_id,
                    'first_name' => $parts[0],
                    'last_name'  => $parts[1] ?? '',
                ],
                [
                    'contact' => $beneficiary->contact,
                ]
            );

            $beneficiary->person_id = $person->id;
        });
    }

}
