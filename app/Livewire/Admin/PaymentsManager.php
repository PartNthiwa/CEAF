<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class PaymentsManager extends Component
{
   public function render()
    {
        return view('livewire.admin.payments-manager')
            ->layout('layouts.admin');
    }
}
