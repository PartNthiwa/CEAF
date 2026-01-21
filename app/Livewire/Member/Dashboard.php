<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{

    public $membershipStatus;

    public function mount()
    {
        $this->membershipStatus = Auth::user()
            ->member
            ->membership_status;
    }

    public function render()
    {
        return view('livewire.member.dashboard')
         ->layout('layouts.app');
    }
}
