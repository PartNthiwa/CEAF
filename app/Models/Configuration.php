<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = [
        'year',
       'amount_per_event',
       'number_of_events',
    ];

    public static function getCurrentConfiguration()
    {
        $currentYear = date('Y');
        return self::where('year', $currentYear)->first();
    }

    
}
