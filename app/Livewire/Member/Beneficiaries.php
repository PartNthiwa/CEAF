<?php

namespace App\Livewire\Member;

use Livewire\Component;

class Beneficiaries extends Component
{
    public $full_name;
    public $relationship;
    public $percentage;

    public function submitChangeRequest()
    {
        $this->validate([
            'full_name' => 'required',
            'relationship' => 'required',
            'percentage' => 'required|numeric|min:1|max:100',
        ]);

        auth()->user()->member->beneficiaryChangeRequests()->create([
            'payload' => [
                'full_name' => $this->full_name,
                'relationship' => $this->relationship,
                'percentage' => $this->percentage,
            ],
        ]);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.member.beneficiaries', [
            'beneficiaries' => auth()->user()->member->beneficiaries,
        ])->layout('layouts.app');
    }
}
