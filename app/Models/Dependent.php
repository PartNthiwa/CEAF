<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use Illuminate\Validation\ValidationException;
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
    public function person()
    {
        return $this->belongsTo(Person::class);
    }


    // ─── Guards ────────────────────────────────────

   protected static function booted()
    {

       static::creating(function ($dependent) {
            if ($dependent->person_id) return;

            $parts = preg_split('/\s+/', trim($dependent->name), 2);

            $person = Person::firstOrCreate(
                [
                    'member_id'  => $dependent->member_id,
                    'first_name' => $parts[0],
                    'last_name'  => $parts[1] ?? '',
                ],
                [
                    'contact' => $dependent->contact,
                ]
            );

            $dependent->person_id = $person->id;
        });

        static::updating(function ($dependent) {
            if ($dependent->getOriginal('status') === 'deceased') {
                throw ValidationException::withMessages([
                    'status' => 'This dependent is marked as deceased and cannot be modified.',
                ]);
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

    public function isLocked(): bool
    {
        return $this->isDeceased();
    }
}
