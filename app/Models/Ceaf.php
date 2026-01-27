<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Ceaf extends Authenticatable
{
    use HasFactory;

    protected $table = 'ceaf';
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

       protected $hidden = [
        'password',
    ];
}
