<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BeneficiaryChangeRequest;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\DB;

class BeneficiaryRequests extends Component
{use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $currentPayload = [];
    public $modalOpen = false;

    public function render()
    {
        $requests = BeneficiaryChangeRequest::latest()->paginate(8);

        return view('livewire.admin.beneficiary-requests', [
            'requests' => $requests,
        ])->layout('layouts.admin');
    }

    // Show payload in modal
    public function viewPayload($id)
    {
        $request = BeneficiaryChangeRequest::findOrFail($id);
        $this->currentPayload = $request->payload;
        $this->modalOpen = true;
    }

    // Approve request
  public function approve(BeneficiaryChangeRequest $request)
{
    DB::transaction(function () use ($request) {

        foreach ($request->payload as $entry) {

            // UPDATE existing beneficiary
            if (!empty($entry['beneficiary_id'])) {

                Beneficiary::where('id', $entry['beneficiary_id'])
                    ->where('member_id', $request->member_id)
                    ->update([
                        'name' => $entry['name'],
                        'contact' => $entry['contact'],
                        'percentage' => $entry['percentage'],
                    ]);

            } 
            // CREATE new beneficiary
            else {

                Beneficiary::create([
                    'member_id' => $request->member_id,
                    'name' => $entry['name'],
                    'contact' => $entry['contact'],
                    'percentage' => $entry['percentage'],
                ]);
            }
        }

        // Mark request as approved
        $request->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
    });

    session()->flash('success', 'Beneficiary change request approved successfully.');
}


    // Reject request
    public function reject($id)
    {
        $request = BeneficiaryChangeRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);
        $this->reset(['currentPayload', 'modalOpen']);
    }

    public function closeModal()
    {
        $this->reset(['currentPayload', 'modalOpen']);
    }
}
