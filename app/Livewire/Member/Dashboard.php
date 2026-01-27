<?php


namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Models\Dependent;
use App\Models\Beneficiary;
use App\Models\User;
use App\Models\Person;
use App\Models\Event;
use App\Services\PaymentService;
use App\Services\SeedPaymentService;
use App\Services\ReplenishmentService;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventApprovedMail;

use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{

    use WithPagination;
    public $membershipStatus;
    public $amountDue = null;     
    public $nextDeadline = null; 

    public $dependentsCount;
    public $activeDependents;
    public $deceasedDependents;

    public $beneficiariesCount;
    public $pendingBeneficiaryChanges;

    public function mount()
    {
        $member = auth()->user()->member;

        $this->membershipStatus = $member->membership_status ?? 'unknown';

        // Calculate total amount due from pending and late payments
         $this->amountDue = $member->payments()
            ->whereIn('status', ['pending', 'late'])
            ->sum('amount_due');

        // Determine next payment deadline 
        $nextPayment = Payment::query()
            ->where('payments.member_id', $member->id)
            ->whereIn('payments.status', ['pending', 'late'])
            ->join('payment_cycles', 'payments.payment_cycle_id', '=', 'payment_cycles.id')
            ->orderBy('payment_cycles.due_date', 'asc')
            ->select('payments.*')
            ->with('paymentCycle')
            ->first();

        $this->nextDeadline = $nextPayment?->paymentCycle?->due_date;

        // Dependents summary
        $this->dependentsCount = $member->dependents()->count();
        $this->activeDependents = $member->dependents()->where('status', 'active')->count();
        $this->deceasedDependents = $member->dependents()->where('status', 'deceased')->count();

        // Beneficiaries summary
        $this->beneficiariesCount = $member->beneficiaries()->count();
        $this->pendingBeneficiaryChanges = $member->beneficiaries()
            ->where('status', 'pending')->count();
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
