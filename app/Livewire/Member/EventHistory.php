<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $member = auth()->user()->member;

        $events = Event::with(['person', 'documents'])
            ->where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.member.event-history', [
            'events' => $events,
        ])->layout('layouts.app');
    }
}
