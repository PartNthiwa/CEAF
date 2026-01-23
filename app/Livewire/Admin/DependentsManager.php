<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Dependent;
use Livewire\WithPagination;

class DependentsManager extends Component
{
    use WithPagination;

    public $search = '';
    public $relationship = '';
    public $status = '';

    protected $paginationTheme = 'tailwind';
    protected $updatesQueryString = ['search', 'relationship', 'status', 'page'];

    // Track the dependent being edited
    public $editDependentId;
    public $editName;
    public $editRelationship;
    public $editStatus;
    public $editProfileComplete;

    protected $listeners = ['refreshComponent' => '$refresh'];
    public function updatingSearch() { $this->resetPage(); }
    public function updatingRelationship() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function edit(Dependent $dependent)
    {
        $this->editDependentId = $dependent->id;
        $this->editName = $dependent->name;
        $this->editRelationship = $dependent->relationship;
        $this->editStatus = $dependent->status;
        $this->editProfileComplete = $dependent->profile_complete;
    }

    public function save()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editRelationship' => 'required|string',
            'editStatus' => 'required|string',
            'editProfileComplete' => 'boolean',
        ]);


         // Check for duplicates
       $dependent = Dependent::find($this->editDependentId);

        if (!$dependent) {
            $this->addError('notfound', 'Dependent not found.');
            return;
        }

        $exists = Dependent::where('member_id', $dependent->member_id)
            ->where('name', $this->editName)
            ->where('relationship', $this->editRelationship)
            ->where('id', '!=', $this->editDependentId)
            ->exists();

        if ($exists) {
            $this->addError('duplicate', 'A dependent with this name and relationship already exists for this member.');
            return;
        }


  
        $dependent = Dependent::find($this->editDependentId);
        $dependent->update([
            'name' => $this->editName,
            'relationship' => $this->editRelationship,
            'status' => $this->editStatus,
            'profile_complete' => $this->editProfileComplete,
        ]);

        session()->flash('message', 'Dependent updated successfully!');
        $this->reset(['editDependentId', 'editName', 'editRelationship', 'editStatus', 'editProfileComplete']);
    }

    public function toggleComplete(Dependent $dependent)
    {
        $dependent->update([
            'profile_completed' => !$dependent->profile_completed,
        ]);
        session()->flash('message', 'Dependent profile completion status updated.');
    }

    public function delete($id)
    {
        $dependent = Dependent::findOrFail($id);
        $dependent->delete();

        session()->flash('message', 'Dependent deleted successfully.');
    }


    public function render()
    {
        $query = Dependent::with('member.user')
            ->when($this->search, fn($q) => $q->whereHas('member.user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%")))
            ->when($this->relationship, fn($q) => $q->where('relationship', $this->relationship))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy('created_at', 'desc');

        $dependents = $query->paginate(15);

        return view('livewire.admin.dependents-manager', compact('dependents'))
            ->layout('layouts.admin');
    }
}
