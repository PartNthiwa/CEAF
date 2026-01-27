<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentCycle;
use App\Services\SeedPaymentService;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Dashboard extends Component
{
    use WithPagination;

    /* =====================
     * MEMBER HEALTH
     * ===================== */
    public int $totalMembers = 0;
    public int $activeMembers = 0;
    public int $lateMembers = 0;
    public int $suspendedMembers = 0;
    public int $terminatedMembers = 0;

    /* =====================
     * FINANCIAL HEALTH
     * ===================== */
    public float $seedBalance = 0;
    public float $seedSpent = 0;
    public int $openReplenishments = 0;

    /* =====================
     * ALERTS
     * ===================== */
    public Collection $alerts;

    protected $updatesQueryString = ['page'];

    public function list()
    {
        $members = Member::with('user')
            ->paginate(20);

        return view('livewire.admin.mlist', compact('members'))->layout('layouts.admin');
    }

    public function mount()
    {
        $year = now()->year;

        /* ---- Member health ---- */
        $this->totalMembers     = Member::count();
        $this->activeMembers    = Member::where('membership_status', 'active')->count();
        $this->lateMembers      = Member::where('membership_status', 'late')->count();
        $this->suspendedMembers = Member::where('membership_status', 'suspended')->count();
        $this->terminatedMembers= Member::where('membership_status', 'terminated')->count();

        /* ---- Financial health ---- */
        $this->seedSpent   = SeedPaymentService::totalSpent($year);
        $this->seedBalance = SeedPaymentService::balance($year);

        $this->openReplenishments = PaymentCycle::where('type', 'replenishment')
            ->where('status', 'open')
            ->count();

        /* ---- Alerts ---- */
        $this->alerts = collect();

        if ($this->lateMembers > 0) {
            $this->alerts->push("{$this->lateMembers} member(s) have late payments.");
        }

        if ($this->suspendedMembers > 0) {
            $this->alerts->push("{$this->suspendedMembers} member(s) are suspended.");
        }

        if ($this->seedBalance < 0) {
            $this->alerts->push("Seed fund balance is negative.");
        }
    }

    /* =====================
     * ACTIONS
     * ===================== */
    public function enforceLatePayments()
    {
        \Artisan::call('enforce:late-payments');

        session()->flash('success', 'Late payment enforcement executed.');
    }

    /* =====================
     * RENDER
     * ===================== */
    public function render()
    {
        $members = Member::with('user')
            ->withSum(
                ['payments as amount_due' => fn ($q) =>
                    $q->whereIn('status', ['pending', 'late'])
                ],
                'amount_due'
            )
           ->with([
                'payments' => fn ($q) =>
                    $q->whereIn('status', ['pending', 'late'])
                    ->whereHas('paymentCycle')
                    ->with('paymentCycle')
                    ->orderBy(
                        PaymentCycle::select('due_date')
                            ->whereColumn('payment_cycles.id', 'payments.payment_cycle_id')
                    )
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        // Attach next_deadline dynamically
        $members->getCollection()->transform(function ($member) {
            $member->next_deadline = optional(
                $member->payments->first()
            )->paymentCycle?->due_date;

            return $member;
        });

        return view('livewire.admin.dashboard', [
            'members' => $members,
        ])->layout('layouts.admin');
    }
}