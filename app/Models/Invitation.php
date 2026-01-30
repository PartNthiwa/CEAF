<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Invitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'used_at',
        'invited_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && now()->gt($this->expires_at);
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }
}