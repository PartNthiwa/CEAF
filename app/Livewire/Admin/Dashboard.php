<?php


namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Member;

class Dashboard extends Component
{

    public $totalMembers;
    public $activeMembers;
    public $lateMembers;
    public $suspendedMembers;

    public function mount()
    {
        $this->totalMembers = Member::count();
        $this->activeMembers = Member::where('membership_status', 'active')->count();
        $this->lateMembers = Member::where('membership_status', 'late')->count();
        $this->suspendedMembers = Member::where('membership_status', 'suspended')->count();
    }
    
    public function render()
    {
        return view('livewire.admin.dashboard')
          ->layout('layouts.app');
    }
}
