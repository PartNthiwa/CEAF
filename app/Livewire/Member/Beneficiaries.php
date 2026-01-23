<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Dependent;
use App\Models\BeneficiaryChangeRequest;
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
    public $selectedPerson; 


public function enableChangeRequest()
{
    $member = auth()->user()->member;

    $pendingRequest = BeneficiaryChangeRequest::where('member_id', $member->id)
        ->where('status', 'pending')
        ->first();

    if ($pendingRequest) {
        session()->flash('error', 'You already have a beneficiary change request under review.');
        return;
    }

    $this->changeMode = true;
    $this->openModal();
}

public function submitChangeRequest()
{
    $member = auth()->user()->member;

    // Build payload from existing beneficiaries
    $payload = $member->beneficiaries->map(function ($b) {
        return [
            'beneficiary_id' => $b->id,
            'name' => $b->name,
            'contact' => $b->contact,
            'percentage' => $b->percentage,
        ];
    })->values()->toArray();

    // ---------------------------
    // Resolve selected person from dropdown
    // ---------------------------
    if (!$this->selectedPerson) {
        session()->flash('error', 'Please select a beneficiary or dependent.');
        return;
    }

    [$type, $id] = explode(':', $this->selectedPerson);

    if ($type === 'beneficiary') {
        $existing = $member->beneficiaries()->find($id);
        if (!$existing) {
            session()->flash('error', 'Selected beneficiary not found.');
            return;
        }

        $newEntry = [
            'beneficiary_id' => $existing->id,
            'name' => $existing->name,
            'contact' => $existing->contact ?? '-',
            'percentage' => $this->percentage,
        ];

    } else { // dependent
        $dependent = Dependent::find($id);
        if (!$dependent || $dependent->status === 'deceased') {
            session()->flash('error', 'Invalid dependent selected.');
            return;
        }

        if (!$dependent->profile_completed) {
            session()->flash('error', 'Cannot submit a change request for a dependent whose profile is incomplete.');
            return;
        }

        $existing = $member->beneficiaries()->where('name', $dependent->name)->first();

        $newEntry = [
            'beneficiary_id' => $existing?->id,
            'name' => $dependent->name,
            'contact' => $dependent->contact ?? '-',
            'percentage' => $this->percentage,
        ];
    }

    // ---------------------------
    // Merge payload: update if exists, append if new
    // ---------------------------
    $payload = collect($payload)
        ->map(function ($item) use ($newEntry) {
            // Update if same beneficiary_id or same name
            if (
                ($newEntry['beneficiary_id'] && $item['beneficiary_id'] === $newEntry['beneficiary_id']) ||
                (!$newEntry['beneficiary_id'] && strtolower($item['name']) === strtolower($newEntry['name']))
            ) {
                return $newEntry;
            }
            return $item;
        })
        ->when(
            // Append only if not already in payload
            collect($payload)->doesntContain(fn ($item) =>
                ($newEntry['beneficiary_id'] && $item['beneficiary_id'] === $newEntry['beneficiary_id']) ||
                strtolower($item['name']) === strtolower($newEntry['name'])
            ),
            fn ($collection) => $collection->push($newEntry)
        )
        ->values()
        ->toArray();

    // ---------------------------
    // Save change request
    // ---------------------------
    BeneficiaryChangeRequest::create([
        'member_id' => $member->id,
        'payload' => $payload,
        'status' => 'pending',
    ]);

    // Reset modal and form
    $this->reset(['selectedPerson', 'percentage']);
    $this->changeMode = false;
    $this->closeModal();

    session()->flash('success', 'Beneficiary change request submitted for admin approval.');
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
        $people = collect([]);

        $member->beneficiaries->each(function($b) use (&$people) {
            $people->push([
                'id' => $b->id,
                'type' => 'beneficiary',
                'name' => $b->name,
                'contact' => $b->contact,
                'percentage' => $b->percentage,
            ]);
        });

        $member->dependents->where('status', 'active')->each(function($d) use (&$people) {
            $people->push([
                'id' => $d->id,
                'type' => 'dependent',
                'name' => $d->name,
                'contact' => $d->contact ?? '-',
            ]);
        });
        $pendingChangeRequest = BeneficiaryChangeRequest::where('member_id', auth()->user()->member->id)
            ->where('status', 'pending')
            ->first();
        return view('livewire.member.beneficiaries', [
              'people' => $people,
            'beneficiaries' => $member->beneficiaries()->latest()->paginate(5),
            'dependents' => $member->dependents()->where('status', 'active')->get(),
            'pendingChangeRequest' => $pendingChangeRequest,
        ])->layout('layouts.app');
    }
}
