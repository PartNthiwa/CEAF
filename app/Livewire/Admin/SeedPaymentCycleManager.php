<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Configuration;
use App\Models\Member;
use App\Models\PaymentCycle;
use App\Models\Payment;
use Carbon\Carbon;

class SeedPaymentCycleManager extends Component
{
    public $year;
    public $amount_per_member;
    public $start_date;
    public $due_date;
    public $late_deadline;

    protected $rules = [
        'year' => 'required|integer|min:2000',
        'amount_per_member' => 'required|numeric|min:1',
        'start_date' => 'required|date',
        'due_date' => 'required|date|after_or_equal:start_date',
        'late_deadline' => 'required|date|after_or_equal:due_date',
    ];

    public function mount()
    {
        $this->year = now()->year;

        $config = Configuration::where('year', $this->year)->first();

        if ($config) {
            $activeMembers = Member::where('membership_status', 'active')->count();
            $totalSeedAmount = $config->amount_per_event * $config->number_of_events;

            $this->amount_per_member = $activeMembers > 0
                ? round($totalSeedAmount / $activeMembers, 2)
                : 0;
        }
    }


    public function createSeedCycle()
    {
        $this->validate();

        $cycle = PaymentCycle::create([
            'type' => 'seed',
            'year' => $this->year,
            'amount_per_member' => $this->amount_per_member,
            'start_date' => Carbon::parse($this->start_date),
            'due_date' => Carbon::parse($this->due_date),
            'late_deadline' => Carbon::parse($this->late_deadline),
            'status' => 'open',
        ]);

        $members = Member::where('membership_status', 'active')->get();

        foreach ($members as $member) {
            Payment::create([
                'payment_cycle_id' => $cycle->id,
                'member_id' => $member->id,
                'amount_due' => $this->amount_per_member,
                'status' => 'pending',
            ]);
        }

        session()->flash('success', 'Seed payment cycle created successfully.');
    }

    public function render()
    {
        return view('livewire.admin.seed-payment-cycle-manager')
            ->layout('layouts.admin');
    }
}