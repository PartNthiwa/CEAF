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
use App\Models\EventDetail;
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
    public $statusUi;

    public $beneficiariesCount;
    public $pendingBeneficiaryChanges;
    public $approvedEvents;
    public function mount()
    {
        $member = auth()->user()->member;

        $this->membershipStatus = $member->membership_status ?? 'unknown';

        //Ui Ststus Map
        $this->statusUi = match ($this->membershipStatus) {
                'active' => [
                    'label' => 'Active',
                    'class' => 'bg-green-600',
                    'description' => 'Your membership is in good standing.',
                ],

                'late' => [
                    'label' => 'Action required',
                    'class' => 'bg-amber-500',
                    'description' => 'Payment overdue or pending requirements.',
                ],

                'suspended' => [
                    'label' => 'Suspended',
                    'class' => 'bg-red-600',
                    'description' => 'Access is restricted until issues are resolved.',
                ],

                'terminated' => [
                    'label' => 'Terminated',
                    'class' => 'bg-gray-700',
                    'description' => 'Membership has been permanently closed.',
                ],

                default => [
                    'label' => 'Unknown',
                    'class' => 'bg-gray-400',
                    'description' => 'Status could not be determined.',
                ],
            };

        // Calculate total amount due from pending and late payments
        $this->amountDue = $member->payments()
                ->whereIn('status', ['pending', 'late'])
                ->sum(DB::raw('amount_due - amount_paid'));
         $this->approvedEvents = Event::where('status', 'approved')
            ->whereHas('details', function ($query) {
                $query->where('event_date', '>', now());
            })
            ->with('details') 
            ->get();
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
        $dependents = auth()->user()->member->dependents()->latest()->paginate(5);

        return view('livewire.member.dashboard', [
            'dependents' => $dependents,
        ])->layout('layouts.app');
    }


}
