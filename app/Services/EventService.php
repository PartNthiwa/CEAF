<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Dependent;
use App\Models\AuditLog;
use Carbon\Carbon;
use App\Services\SeedPaymentService;
use App\Services\ReplenishmentService;

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

    $approvedAmount = $event->amount; // or computed logic

    // Approve event FIRST
    $event->update([
        'status' => 'approved',
        'approved_amount' => $approvedAmount,
        'approved_at' => now(),
    ]);

    $year = $event->approved_at->year;

    // Try seed fund
    if (SeedPaymentService::hasSufficientBalance($approvedAmount, $year)) {

        SeedPaymentService::deductForEvent($event);

    } else {

        // Trigger replenishment
        ReplenishmentService::trigger($approvedAmount, $year);

        $event->update([
            'paid_from_seed' => false,
            'requires_replenishment' => true,
        ]);
    }

    // Audit
    AuditLog::create([
        'user_id' => $actorId,
        'action' => 'event_approved',
        'model' => Event::class,
        'model_id' => $event->id,
        'new_values' => [
            'status' => 'approved',
            'approved_amount' => $approvedAmount,
        ],
    ]);
}
    // public function approve(Event $event, int $actorId): void
    // {
    //     if ($event->status !== 'under_review') {
    //         throw new \Exception('Event must be under review.');
    //     }

    //     $event->update([
    //         'status' => 'approved',
    //         'approved_at' => Carbon::now(),
    //     ]);

    //     AuditLog::create([
    //         'user_id' => $actorId,
    //         'action' => 'event_approved',
    //         'model' => Event::class,
    //         'model_id' => $event->id,
    //         'new_values' => ['status' => 'approved'],
    //     ]);
    // }

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

