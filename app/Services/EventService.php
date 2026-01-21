<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Dependent;
use App\Models\AuditLog;
use Carbon\Carbon;

class EventService
{
    public function submit(int $memberId, ?Dependent $dependent = null): Event
    {
        return Event::create([
            'member_id' => $memberId,
            'dependent_id' => $dependent?->id,
            'status' => 'submitted',
        ]);
    }

    public function approve(Event $event, int $actorId): void
    {
        if ($event->status !== 'under_review') {
            throw new \Exception('Event must be under review.');
        }

        $event->update([
            'status' => 'approved',
            'approved_at' => Carbon::now(),
        ]);

        AuditLog::create([
            'user_id' => $actorId,
            'action' => 'event_approved',
            'model' => Event::class,
            'model_id' => $event->id,
            'new_values' => ['status' => 'approved'],
        ]);
    }

    public function reject(Event $event, int $actorId): void
    {
        if (! in_array($event->status, ['submitted', 'under_review'])) {
            throw new \Exception('Invalid event state.');
        }

        $event->update(['status' => 'rejected']);

        AuditLog::create([
            'user_id' => $actorId,
            'action' => 'event_rejected',
            'model' => Event::class,
            'model_id' => $event->id,
            'new_values' => ['status' => 'rejected'],
        ]);
    }
}

