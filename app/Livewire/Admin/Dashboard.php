<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Member;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $totalMembers;
    public $activeMembers;
    public $lateMembers;
    public $suspendedMembers;

    protected $updatesQueryString = ['page'];

    public function mount()
    {
        $this->totalMembers = Member::count();
        $this->activeMembers = Member::where('membership_status', 'active')->count();
        $this->lateMembers = Member::where('membership_status', 'late')->count();
        $this->suspendedMembers = Member::where('membership_status', 'suspended')->count();
    }

    public function render()
    {
        // Join members with users to get name and email
        $members = Member::with('user') // eager load user
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('livewire.admin.dashboard', [
            'members' => $members,
        ])->layout('layouts.admin');
    }
}
