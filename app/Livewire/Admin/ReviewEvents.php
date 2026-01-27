<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentService;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Person;
use App\Models\Beneficiary;
use App\Models\Dependent;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Services\SeedPaymentService;
use App\Services\ReplenishmentService;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventApprovedMail;

class ReviewEvents extends Component
{

    public $search = '';
    use WithPagination;

    public function approve($eventId)
    {
        DB::transaction(function () use ($eventId) {

            $event = Event::with(['person', 'member'])->findOrFail($eventId);
            $member = $event->member;

            // Update person and beneficiaries
            $event->person->update([
                'deceased_at' => now(),
                'status'      => 'deceased',
            ]);

            $event->person->beneficiaries()->update([
                'percentage' => 0,
                'status'     => 'deceased', 
            ]);

            // Handle payments
            $approvedAmount = $event->amount; // assumed property
            $currentYear = now()->year;

            // Check seed fund
            if (SeedPaymentService::hasSufficientBalance($approvedAmount, $currentYear)) {
                SeedPaymentService::deductForEvent($event);
            } else {
                // Trigger replenishment if seed insufficient
                ReplenishmentService::trigger($approvedAmount, $currentYear);
            }

            // Update event status
            $event->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_amount' => $approvedAmount,
                'paid_from_seed' => SeedPaymentService::hasSufficientBalance($approvedAmount, $currentYear),
            ]);

            // Optional: Audit logging here
        });

        session()->flash('success', 'Event approved successfully.');
        $this->resetPage();
    }

    public function reject($eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->update(['status' => 'rejected']);
        session()->flash('success', 'Event rejected.');
       $this->resetPage();
    }

  public function render()
{
     $events = Event::with(['person', 'member', 'documents','paymentCycle'])
            ->when($this->search, function ($query) {
                $query->whereHas('person', fn($q) =>
                    $q->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                );
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.review-events', [
            'events' => $events,
        ])->layout('layouts.admin');
    }


}