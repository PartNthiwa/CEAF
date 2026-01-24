<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';
    protected $fillable = [
            'member_id',
            'first_name',
            'last_name',
            'contact',
            'status',
            'deceased_at',
    ];

    protected $casts = [
        'deceased_at' => 'datetime',
    ];

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }


    public function dependent()
    {
        return $this->hasOne(Dependent::class);
    }

    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class);
    }

    public function isDeceased(): bool
    {
        return !is_null($this->deceased_at);
    }

    public function getRolesLabelAttribute()
    {
        $roles = [];

        if ($this->dependent) {
            $roles[] = 'Dependent';
        }

        if ($this->beneficiary) {
            $roles[] = 'Beneficiary';
        }

        return implode(' / ', $roles);
    }

     public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function scopeAlive($query)
    {
        return $query->whereNull('deceased_at')
                    ->where('status', 'active');
    }

}
