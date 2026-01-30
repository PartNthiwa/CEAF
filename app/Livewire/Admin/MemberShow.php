<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Member;
use Illuminate\Support\Collection;

class MemberShow extends Component
{
    public Member $member;

    public Collection $beneficiaries;
    public Collection $dependents;
    public Collection $events;
    public Collection $payments;

    // Computed summaries
    public float $amountDue = 0.0;
    public ?string $nextDeadline = null;
    public int $beneficiaryAllocationTotal = 0;

    public function mount(Member $member): void
    {
        $this->member = $member;
        $this->loadAll();
    }

    public function loadAll(): void
    {
        // Load everything needed for the show blade
        $this->member = Member::with([
            'user',
            'beneficiaries',
            'dependents',
            'events' => fn ($q) => $q->latest(),
            'payments' => fn ($q) => $q->with('paymentCycle')->latest(),
        ])->findOrFail($this->member->id);

        $this->beneficiaries = collect($this->member->beneficiaries ?? []);
        $this->dependents    = collect($this->member->dependents ?? []);
        $this->events        = collect($this->member->events ?? []);
        $this->payments      = collect($this->member->payments ?? []);

        // Beneficiary allocation total
        $this->beneficiaryAllocationTotal = (int) $this->beneficiaries->sum('percentage');

        // Compute Amount Due correctly:
        // due = max(0, (amount_due + late_fee) - amount_paid)
        $this->amountDue = (float) $this->payments
            ->filter(fn ($p) => in_array($p->status, ['pending', 'late'], true))
            ->sum(function ($p) {
                $expected = (float) ($p->amount_due ?? 0);
                $lateFee  = (float) ($p->late_fee ?? 0);
                $paid     = (float) ($p->amount_paid ?? 0);

                return max(0, ($expected + $lateFee) - $paid);
            });

        // Next deadline: earliest due_date among payments that still have outstanding due
        $next = $this->payments
            ->filter(fn ($p) => in_array($p->status, ['pending', 'late'], true))
            ->filter(function ($p) {
                $expected = (float) ($p->amount_due ?? 0);
                $lateFee  = (float) ($p->late_fee ?? 0);
                $paid     = (float) ($p->amount_paid ?? 0);

                return max(0, ($expected + $lateFee) - $paid) > 0;
            })
            ->sortBy(fn ($p) => optional($p->paymentCycle)->due_date)
            ->first();

        $this->nextDeadline = optional(optional($next)->paymentCycle)->due_date?->format('d M Y');
    }

    public function refreshData(): void
    {
        $this->loadAll();
        session()->flash('success', 'Member data refreshed.');
    }

    public function goBack()
    {
        return redirect()->route('admin.members-list');
    }

    public function render()
    {
        return view('livewire.admin.show') ->layout('layouts.admin');
    }
}
