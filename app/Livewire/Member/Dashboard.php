<?php


namespace App\Livewire\Member;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{

    public $membershipStatus;
    public $amountDue = null;     
    public $nextDeadline = null; 

    public function mount()
    {
        $member = auth()->user()->member;

        $this->membershipStatus = $member->membership_status ?? 'unknown';

        // Load current seed payment cycle for this member
        $payment = \App\Models\Payment::where('member_id', $member->id ?? 0)
            ->whereHas('paymentCycle', function ($q) {
                $q->where('type', 'seed')
                  ->where('year', now()->year);
            })->first();

        if ($payment) {
            $this->amountDue = $payment->amount_due + $payment->late_fee;
            $this->nextDeadline = $payment->paymentCycle->due_date;
        }
    }

    public function render()
    {
        return view('livewire.member.dashboard')
         ->layout('layouts.app');
    }
}
