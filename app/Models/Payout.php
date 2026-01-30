<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'amount',
        'source_fund',
        'status',
        'transaction_id',
        'payout_at',
        'recipient_email',
        'failure_reason',
        'attempt_count'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
