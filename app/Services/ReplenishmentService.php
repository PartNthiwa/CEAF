<?php

namespace App\Services;

use App\Models\PaymentCycle;
use App\Models\Payment;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;
use Log;

class ReplenishmentService
{
    public static function trigger(
    float $eventAmount,
    int $year,
    ?int $eventId = null
): PaymentCycle {

    $activeMembers = Member::where('membership_status', 'active')->count();

    if ($activeMembers === 0) {
        throw new \Exception('No active members available for replenishment.');
    }

    $amountPerMember = round($eventAmount / $activeMembers, 2);

    return DB::transaction(function () use ($year, $amountPerMember, $eventId) {

        $cycle = PaymentCycle::create([
            'type' => 'replenishment',
            'year' => $year,
            'amount_per_member' => $amountPerMember,
            'trigger_event_id' => $eventId,
            'start_date' => now(),
            'due_date' => now()->addDays(14),
            'late_deadline' => now()->addDays(24),
            'status' => 'open',
        ]);

        Member::where('membership_status', 'active')
            ->each(fn ($member) =>
                Payment::create([
                    'payment_cycle_id' => $cycle->id,
                    'member_id' => $member->id,
                    'amount_due' => $amountPerMember,
                    'status' => 'pending',
                ])
            );

        return $cycle;
    });
}

 public static function createSeedReplenishmentCycle(int $year, float $eventAmount): PaymentCycle
    {
        $currentBalance = SeedPaymentService::balance($year);
        $shortfall = $eventAmount - $currentBalance;

        if ($shortfall <= 0) {
            throw new \Exception('No replenishment needed. Seed fund already sufficient.');
        }

        $activeMembers = Member::where('membership_status', 'active')->count();

        if ($activeMembers === 0) {
            throw new \Exception('No active members available for replenishment.');
        }

        $amountPerMember = round($shortfall / $activeMembers, 2);

        return DB::transaction(function () use ($year, $amountPerMember) {
            // Create the replenishment cycle
            $cycle = PaymentCycle::create([
                'type' => 'replenishment',
                'year' => $year,
                'amount_per_member' => $amountPerMember,
                'start_date' => now(),
                'due_date' => now()->addDays(14),
                'late_deadline' => now()->addDays(24),
                'status' => 'open',
            ]);

            // Create payments for each active member
            Member::where('membership_status', 'active')
                ->each(fn($member) => Payment::create([
                    'payment_cycle_id' => $cycle->id,
                    'member_id' => $member->id,
                    'amount_due' => $amountPerMember,
                    'status' => 'pending',
                ]));

            return $cycle;
        });
    }
}