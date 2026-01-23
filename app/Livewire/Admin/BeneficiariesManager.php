<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Beneficiary;

class BeneficiariesManager extends Component
{
    use WithPagination;

    public $search = '';
    public $editId = null;

    public $editName;
    public $editContact;
    public $editPercentage;
    public $percentageErrorData = null;

    protected $rules = [
        'editName' => 'required|string|max:255',
        'editContact' => 'nullable|string|max:255',
        'editPercentage' => 'required|integer|min:1|max:100',
    ];

    public function edit($id)
    {
        $b = Beneficiary::findOrFail($id);

        $this->editId = $b->id;
        $this->editName = $b->name;
        $this->editContact = $b->contact;
        $this->editPercentage = $b->percentage;
    }

    public function save()
{
    $this->validate();

    $beneficiary = Beneficiary::with('member.user')
        ->findOrFail($this->editId);

    // Existing beneficiaries (excluding current)
    $existing = Beneficiary::where('member_id', $beneficiary->member_id)
        ->where('id', '!=', $beneficiary->id)
        ->get();

    $currentTotal = $existing->sum('percentage');
    $remaining = 100 - $currentTotal;

    if ($this->editPercentage > $remaining) {
        $this->percentageErrorData = [
            'member' => $beneficiary->member->user->name ?? 'Member',
            'current_total' => $currentTotal,
            'remaining' => $remaining,
            'attempted' => $this->editPercentage,
            'beneficiaries' => $existing->map(fn ($b) => [
                'name' => $b->name,
                'percentage' => $b->percentage,
            ]),
        ];

        return;
    }

    $beneficiary->update([
        'name' => $this->editName,
        'contact' => $this->editContact,
        'percentage' => $this->editPercentage,
    ]);

    $this->resetEdit();
    $this->percentageErrorData = null;
}


    public function resetEdit()
    {
        $this->reset(['editId', 'editName', 'editContact', 'editPercentage']);
    }

    public function delete($id)
    {
        Beneficiary::findOrFail($id)->delete();
    }

    public function render()
    {
        $beneficiaries = Beneficiary::with('member.user')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhereHas('member.user', fn ($u) =>
                      $u->where('name', 'like', "%{$this->search}%")
                  );
            })
            ->paginate(10);

        return view('livewire.admin.beneficiaries-manager', compact('beneficiaries'))->layout('layouts.admin'); 
    }
}