<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Configuration;
use App\Models\Member;
use App\Models\PaymentCycle;
use App\Models\Payment;
use App\Services\SeedPaymentService;
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

    public function createSeedCycle()
    {
        $this->validate();

        // Call the service to create the cycle
        $cycle = SeedPaymentService::createForYear(
            $this->year,
            $this->amount_per_member,
            Carbon::parse($this->start_date),
            Carbon::parse($this->due_date),
            Carbon::parse($this->late_deadline)
        );

        session()->flash('success', "Seed payment cycle for {$this->year} created successfully.");

        $this->reset(['year','amount_per_member','start_date','due_date','late_deadline']);
    }

    public function render()
    {
        return view('livewire.admin.seed-payment-cycle-manager')
            ->layout('layouts.admin');
    }
}
