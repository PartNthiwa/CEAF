<?php


namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{

    use WithPagination;
    public $membershipStatus;
    public $amountDue = null;     
    public $nextDeadline = null; 

    protected $paginationTheme = 'tailwind'; 

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
        $user = auth()->user();

        $dependents = $user->dependents()->latest()->paginate(5);

        return view('livewire.member.dashboard', [
            'membershipStatus' => $user->membership_status,
            'amountDue' => $user->amount_due,
            'nextDeadline' => $user->next_deadline,
            'dependents' => $dependents,
            'dependentsCount' => $user->dependents()->count(),
            'activeDependents' => $user->dependents()->where('status', 'active')->count(),
            'deceasedDependents' => $user->dependents()->where('status', 'deceased')->count(),
            'beneficiariesCount' => $user->beneficiaries()->count(),
            'pendingBeneficiaryChanges' => $user->beneficiaryChangeRequests()->where('status', 'pending')->count(),
        ])->layout('layouts.app');
    }

}
