<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beneficiary extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'contact',
        'percentage',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
