<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key)
    {
        return self::where('key', $key)->first()->value ?? null;
    }

    public static function set($key, $value)
    {
        $setting = self::firstOrCreate(['key' => $key]);
        $setting->update(['value' => $value]);
    }
}
