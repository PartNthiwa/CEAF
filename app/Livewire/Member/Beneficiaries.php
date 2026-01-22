<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Dependent;
use Livewire\WithPagination;


class Beneficiaries extends Component
{
    use WithPagination;
    public $showModal = false;
    protected $paginationTheme = 'tailwind';
    public $changeMode = false; 

    public $selectedDependent = null;
    public $name;
    public $contact;
    public $percentage;


    public function enableChangeRequest()
    {
        $this->changeMode = true;
        $this->openModal(); 
    }


    public function submitChangeRequest()
{
    $member = auth()->user()->member;

    // Build payload (can include existing beneficiaries + proposed changes)
    $payload = $member->beneficiaries->map(function($b) {
        return [
            'name' => $b->name,
            'contact' => $b->contact,
            'percentage' => $b->percentage,
        ];
    })->toArray();

    // Add/modify based on modal inputs
    $newEntry = [];

    if ($this->selectedDependent) {
        $dependent = Dependent::find($this->selectedDependent);
        if (!$dependent || $dependent->status === 'deceased') {
            session()->flash('error', 'Invalid dependent selected.');
            return;
        }
        $newEntry = [
            'name' => $dependent->name,
            'contact' => $dependent->contact ?? '-',
            'percentage' => $this->percentage,
        ];
    } else {
        $this->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);
        $newEntry = [
            'name' => $this->name,
            'contact' => $this->contact,
            'percentage' => $this->percentage,
        ];
    }

    $payload[] = $newEntry;

    // Validate total allocation
    $total = collect($payload)->sum('percentage');
    if ($total > 100) {
        session()->flash('error', "Allocation exceeds 100% (currently: {$total}%)");
        return;
    }

    // Create change request
    \App\Models\BeneficiaryChangeRequest::create([
        'member_id' => $member->id,
        'payload' => json_encode($payload),
        'status' => 'pending',
    ]);

    $this->reset(['selectedDependent', 'name', 'contact', 'percentage']);
    $this->changeMode = false;
    session()->flash('success', 'Beneficiary change request submitted for approval.');
    $this->closeModal();
}
    
    public function openModal()
    {
        $this->reset(['selectedDependent', 'name', 'contact', 'percentage']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }



public function submit()
{
    $member = auth()->user()->member;

    // Resolve beneficiary identity
    if ($this->selectedDependent) {
        $dependent = Dependent::find($this->selectedDependent);

        if (!$dependent || $dependent->status === 'deceased') {
            session()->flash('error', 'Invalid or deceased dependent selected.');
            return;
        }

        $name = $dependent->name;
        $contact = $dependent->contact ?? '-';

    } else {
        $this->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        $name = $this->name;
        $contact = $this->contact;
    }

    // Validate percentage
    $this->validate([
        'percentage' => 'required|integer|min:1|max:100',
    ]);

    // --- ENFORCE TOTAL <= 100% ---
    $totalAllocated = $member->beneficiaries()->sum('percentage');
    $proposedTotal = $totalAllocated + $this->percentage;

    if ($proposedTotal > 100) {
        session()->flash('error', "Cannot add beneficiary: allocation exceeds 100% (current: $totalAllocated%)");
        return;
    }

    // Create beneficiary
    $member->beneficiaries()->create([
        'name' => $name,
        'contact' => $contact,
        'percentage' => $this->percentage,
    ]);

    $this->reset();
    session()->flash('success', 'Beneficiary added successfully.');

    $this->closeModal();
}


public function getRemainingAllocationProperty()
{
    $member = auth()->user()->member;
    $allocated = $member->beneficiaries()->sum('percentage');

    return max(100 - $allocated, 0);
}


     public function render()
    {
        $member = auth()->user()->member;

        return view('livewire.member.beneficiaries', [
            'beneficiaries' => $member->beneficiaries()->latest()->paginate(5),
            'dependents' => $member->dependents()->where('status', 'active')->get(),
        ])->layout('layouts.app');
    }
}
