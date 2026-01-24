<?php

namespace App\Services;

use App\Models\PaymentCycle;
use App\Models\Payment;
use App\Models\Member;
use Carbon\Carbon;

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
}}