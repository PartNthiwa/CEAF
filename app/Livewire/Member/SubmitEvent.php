<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Person;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class SubmitEvent extends Component
{
    use WithFileUploads;

    public $person_id;
    public $death_cert;
    public $burial_permit;
    public $newspaper;

    public function getEligiblePersonsProperty()
    {
        $member = auth()->user()->member;

       return Person::alive()
            ->where('member_id', $member->id)
            ->whereDoesntHave('event')
            ->with(['dependent', 'beneficiary'])
            ->orderBy('first_name')
            ->get();
    }

    public function submit()
    {
        $this->validate([
            'person_id'     => 'required|exists:persons,id',
            'death_cert'    => 'required|file|max:2048',
            'burial_permit' => 'required|file|max:2048',
            'newspaper'     => 'required|file|max:2048',
        ]);

        $member = auth()->user()->member;

        $person = Person::alive()
                ->where('id', $this->person_id)
                ->where('member_id', $member->id)
                ->whereDoesntHave('event')
                ->firstOrFail();

        DB::transaction(function () use ($member, $person) {

            $event = Event::create([
                'member_id' => $member->id,
                'person_id' => $person->id,
                'status'    => 'submitted',
            ]);

            $event->documents()->createMany([
                [
                    'type' => 'death_cert',
                    'path' => $this->death_cert->store('events'),
                ],
                [
                    'type' => 'burial_permit',
                    'path' => $this->burial_permit->store('events'),
                ],
                [
                    'type' => 'newspaper',
                    'path' => $this->newspaper->store('events'),
                ],
            ]);
        });

        session()->flash('success', 'Event submitted successfully.');
        $this->reset();
    }

    public function render()
    {
       $memberId = auth()->user()->member->id;

        return view('livewire.member.submit-event', [
            'persons' => Person::eligibleForEvent($memberId)
                ->with(['dependent', 'beneficiary'])
                ->orderBy('first_name')
                ->get(),
        ])->layout('layouts.app');
    }
}