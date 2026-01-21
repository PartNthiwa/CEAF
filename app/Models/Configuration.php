<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = [
        'year',
        'key',
        'value',
    ];

    public static function get(int $year, string $key, $default = null)
    {
        return static::where('year', $year)
            ->where('key', $key)
            ->value('value') ?? $default;
    }
}
