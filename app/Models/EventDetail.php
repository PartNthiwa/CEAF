<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'event_date',
        'location',
        'image_path'
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
