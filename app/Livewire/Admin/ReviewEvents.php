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

class ReviewEvents extends Component
{

    public $search = '';
    use WithPagination;

    public function approve($eventId)
    {
        DB::transaction(function () use ($eventId) {
            $event = Event::with('person')->findOrFail($eventId);

              $event->person->update([
                    'deceased_at' => now(),
                    'status'      => 'deceased',
                ]);

                 $event->person->beneficiaries()->update([
                    'percentage' => 0,
                    'status'     => 'deceased', 
                ]);
            $event->update(['status' => 'approved', 'approved_at' => now()]);

            // Placeholder: trigger payments later
            // PaymentService::handleApprovedEvent($event);
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
     $events = Event::with(['person', 'member', 'documents'])
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