<?php

namespace App\Services;

use App\Models\PaymentCycle;
use App\Models\Payment;
use App\Models\Member;
use Carbon\Carbon;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;
use Throwable;
use App\Services\ReplenishmentService;
use App\Models\AuditLog;
use App\Models\Dependent;
use App\Services\SeedPaymentService;

class SeedPaymentService
{
    public static function createForYear(
    int $year, 
    float $amountPerMember, 
    $startDate = null, 
    $dueDate = null, 
    $lateDeadline = null
) {
    $cycle = PaymentCycle::create([
        'type' => 'seed',
        'year' => $year,
        'amount_per_member' => $amountPerMember,
        'start_date' => $startDate ?? Carbon::create($year,1,1),
        'due_date' => $dueDate ?? Carbon::create($year,2,15),
        'late_deadline' => $lateDeadline ?? Carbon::create($year,2,25),
        'status' => 'open'
    ]);

    $members = \App\Models\Member::where('membership_status','active')->get();

    foreach($members as $member) {
        \App\Models\Payment::create([
            'payment_cycle_id' => $cycle->id,
            'member_id' => $member->id,
            'amount_due' => $amountPerMember,
            'status' => 'pending'
        ]);
    }

    return $cycle;
}

    /** 
     * 
     * Returns the total amount collected for a specific seed payment cycle year -  PAID Only(e.g., 2023).
     * 
     * */
    public static function totalCollected(int $year): float
    {
        return Payment::whereHas('paymentCycle', function ($q) use ($year) {
                $q->where('type', 'seed')
                  ->where('year', $year);
            })
            ->where('status', 'paid')
            ->sum('amount_paid');
    }

    /** 
     * 
     * Checks if there is sufficient balance in the seed fund for a given amount.
     * 
     * */
  public static function hasSufficientBalance(float $amount, int $year): bool
    {
        $balance = self::balance($year);
        Log::info("Checking seed balance for $year: available $balance, required $amount");
        return $balance >= $amount;
    }







    /** 
     * 
     * Deducts a specified amount from the seed fund for the current year.
     * 
     * */
    public static function deductForEvent(Event $event): void
    {
        if ($event->paid_from_seed) {
            return; // already deducted
        }

        $year = $event->approved_at->year;

        if (! self::hasSufficientBalance($event->approved_amount, $year)) {
            throw new \Exception('Insufficient seed fund balance.');
        }

        $event->update([
            'paid_from_seed' => true,
        ]);
    }
    /** 
     * 
     * Returns the total amount spent from the seed fund for approved events in a specific year.
     * 
     * */
    public static function totalSpent(int $year): float
    {
        return Event::whereYear('approved_at', $year)
            ->where('status', 'approved')
            ->where('paid_from_seed', true)
            ->sum('approved_amount');
    }

    /**
     * Current available seed balance
     */
    public static function balance(int $year): float
    {
        return self::totalCollected($year) - self::totalSpent($year);
    }

}