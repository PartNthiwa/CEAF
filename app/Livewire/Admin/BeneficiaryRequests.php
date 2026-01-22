<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BeneficiaryChangeRequest;

class BeneficiaryRequests extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public function render()
    {
        // Fetch latest requests, paginated
        $requests = BeneficiaryChangeRequest::latest()->paginate(8);

        return view('livewire.admin.beneficiary-requests', [
            'requests' => $requests
        ])->layout('layouts.admin'); 
    }
}
