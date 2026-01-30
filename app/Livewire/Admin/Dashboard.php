<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use App\Models\Member;
use App\Models\PaymentCycle;
use App\Services\SeedPaymentService;

class Dashboard extends Component
{
    use WithPagination;

    /* =====================
     * UI STATE
     * ===================== */
    public ?string $statusFilter = null;
    public bool $showMemberModal = false;
    public ?Member $selectedMember = null;

    protected $updatesQueryString = ['page'];

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

    public function mount(): void
    {
        $this->alerts = collect();
        $this->loadSummaryStats();
    }

    /* =====================
     * SUMMARY STATS
     * ===================== */
    private function loadSummaryStats(): void
    {
        $year = now()->year;

        // Member health
        $this->totalMembers      = Member::count();
        $this->activeMembers     = Member::where('membership_status', 'active')->count();
        $this->lateMembers       = Member::where('membership_status', 'late')->count();
        $this->suspendedMembers  = Member::where('membership_status', 'suspended')->count();
        $this->terminatedMembers = Member::where('membership_status', 'terminated')->count();

        // Financial health
        $this->seedSpent   = SeedPaymentService::totalSpent($year);
        $this->seedBalance = SeedPaymentService::balance($year);

        $this->openReplenishments = PaymentCycle::where('type', 'replenishment')
            ->where('status', 'open')
            ->count();

        // Alerts
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
     * FILTERS
     * ===================== */
    public function filterStatus(string $status): void
    {
        $allowed = ['active', 'late', 'suspended', 'terminated', 'all'];

        if (!in_array($status, $allowed, true)) {
            return;
        }

        $this->statusFilter = $status === 'all' ? null : $status;
        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->statusFilter = null;
        $this->resetPage();
    }

    /* =====================
     * MEMBER MODAL
     * ===================== */
    public function openMember(int $memberId): void
    {
        /**
         * Load the member + all related data that the modal needs.
         * Adjust relation names if yours differ:
         * - user
         * - payments.paymentCycle
         * - beneficiaries
         * - dependents
         * - events
         */
        $this->selectedMember = Member::with([
            'user',
            'payments' => fn ($q) => $q->with('paymentCycle')->latest()->take(10),
            'beneficiaries',
            'dependents',
            'events',
        ])->findOrFail($memberId);

        // Optional computed values for modal
        $this->selectedMember->member_number = $this->memberNumber($this->selectedMember->id);
        $this->selectedMember->next_deadline = $this->selectedMember->payments
            ->sortBy(fn ($p) => $p->paymentCycle?->due_date)
            ->first()?->paymentCycle?->due_date;

        $this->showMemberModal = true;
    }

    public function closeMemberModal(): void
    {
        $this->showMemberModal = false;
        $this->selectedMember = null;
    }

    private function memberNumber(int $id): string
    {
        return 'CEAF-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    /* =====================
     * ACTIONS
     * ===================== */
    public function enforceLatePayments(): void
    {
        \Artisan::call('enforce:late-payments');
        session()->flash('success', 'Late payment enforcement executed.');

        // refresh numbers
        $this->loadSummaryStats();
    }

    /* =====================
     * LIST PAGE (optional)
     * ===================== */
    public function list()
    {
        $members = Member::with('user')->paginate(20);

        return view('livewire.admin.mlist', compact('members'))
            ->layout('layouts.admin');
    }

    /* =====================
     * RENDER
     * ===================== */
    public function render()
    {
        $query = Member::query()
            ->with('user')
            ->withSum(
                ['payments as amount_due' => fn ($q) => $q->whereIn('status', ['pending', 'late'])],
                'amount_due'
            )
            ->with([
                // For listing page (deadline calc)
                'payments' => fn ($q) =>
                    $q->whereIn('status', ['pending', 'late'])
                        ->whereHas('paymentCycle')
                        ->with('paymentCycle')
                        ->orderBy(
                            PaymentCycle::select('due_date')
                                ->whereColumn('payment_cycles.id', 'payments.payment_cycle_id')
                        )
            ]);

        if (!empty($this->statusFilter)) {
            $query->where('membership_status', $this->statusFilter);
        }

        $members = $query->orderByDesc('created_at')->paginate(8);

        // Attach next_deadline dynamically for the table
        $members->getCollection()->transform(function ($member) {
            $member->next_deadline = optional($member->payments->first())->paymentCycle?->due_date;
            $member->member_number = $this->memberNumber($member->id);
            return $member;
        });

        return view('livewire.admin.dashboard', [
            'members' => $members,
            'activeFilter' => $this->statusFilter,
        ])->layout('layouts.admin');
    }
}
