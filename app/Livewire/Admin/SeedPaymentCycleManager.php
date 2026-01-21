<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Configuration;
use App\Models\Member;
use App\Models\PaymentCycle;
use App\Models\Payment;

class SeedPaymentCycleManager extends Component
{
    public $year;
    public $cycles;

    public function mount()
    {
        $this->year = now()->year;
        $this->loadCycles();
    }

    public function loadCycles()
    {
        $this->cycles = PaymentCycle::where('type', 'seed')
            ->where('year', $this->year)
            ->withCount(['payments as paid_count' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->get();
    }

    public function createSeedCycle()
    {
        $amountPerEvent = Configuration::get($this->year, 'amount_per_event');
        $eventsPerYear = Configuration::get($this->year, 'events_per_year');
        $seedStart = Configuration::get($this->year, 'seed_payment_start');
        $seedDue = Configuration::get($this->year, 'seed_payment_due');
        $lateDeadline = Configuration::get($this->year, 'seed_late_deadline');

        $totalSeedAmount = $amountPerEvent * $eventsPerYear;

        $activeMembers = Member::where('membership_status', 'active')->get();

        if ($activeMembers->isEmpty()) {
            session()->flash('error', 'No active members available for seed cycle.');
            return;
        }

        $amountPerMember = ceil($totalSeedAmount / $activeMembers->count());

        $cycle = PaymentCycle::create([
            'type' => 'seed',
            'year' => $this->year,
            'amount_per_member' => $amountPerMember,
            'start_date' => $seedStart,
            'due_date' => $seedDue,
            'late_deadline' => $lateDeadline,
            'status' => 'open',
        ]);

        foreach ($activeMembers as $member) {
            Payment::create([
                'payment_cycle_id' => $cycle->id,
                'member_id' => $member->id,
                'amount_due' => $amountPerMember,
                'status' => 'pending',
            ]);
        }

        session()->flash('success', "Seed payment cycle created. Each member owes KES $amountPerMember.");
        $this->loadCycles();
    }

    public function enforceLatePayments()
    {
        $cycle = PaymentCycle::where('type', 'seed')->where('year', $this->year)->first();

        if (!$cycle) return;

        foreach ($cycle->payments as $payment) {
            $member = $payment->member;

            if ($payment->status === 'pending' && now()->greaterThan($cycle->late_deadline)) {
                $lateFeeType = Configuration::get($this->year, 'late_fee_type', 'flat');
                $lateFeeValue = Configuration::get($this->year, 'late_fee_value', 0);

                $payment->late_fee = $lateFeeType === 'flat'
                    ? $lateFeeValue
                    : ceil($payment->amount_due * ($lateFeeValue / 100));

                $payment->status = 'late';
                $payment->save();

                $member->membership_status = 'suspended';
                $member->save();
            }
        }

        $this->loadCycles();
    }

    public function render()
    {
        return view('livewire.admin.seed-payment-cycle-manager')
            ->layout('layouts.app', ['title' => 'Seed Payment Cycle Manager']);
    }
}
