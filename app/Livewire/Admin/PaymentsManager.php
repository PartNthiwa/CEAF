<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Payment;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PaymentsManager extends Component
{
    use WithPagination;
    
   public function render()
    {
       return view('livewire.admin.payments-manager', [
            'payments' => Payment::with(['member.user', 'paymentCycle'])
                ->latest()
                ->paginate(15),
        ])->layout('layouts.admin');
    }
}
