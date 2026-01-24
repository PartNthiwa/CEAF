<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentService;
use Livewire\WithPagination;

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