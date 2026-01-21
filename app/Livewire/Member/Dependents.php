<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithPagination;

class Dependents extends Component
{

     use WithPagination;

    protected $paginationTheme = 'tailwind';
    public $name;
    public $relationship;
    public $date_of_birth;

   public function addDependent()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|in:parent,sibling,child',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = auth()->user();

        // Check for duplicate active dependent
        $exists = $user->dependents()
            ->where('name', $this->name)
            ->where('relationship', $this->relationship)
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            $this->addError('name', 'This dependent already exists.');
            return;
        }

        // Add the dependent
        $user->dependents()->create([
            'name' => $this->name,
            'relationship' => $this->relationship,
            'date_of_birth' => $this->date_of_birth,
        ]);

        // Reset input fields
        $this->reset(['name', 'relationship', 'date_of_birth']);
        $this->resetPage();

        // Flash message
        session()->flash('message', 'Dependent added successfully.');
    }

    public function render()
    {
        return view('livewire.member.dependents', [
          'dependents' => auth()->user()->dependents()->latest()->paginate(5),
        ])->layout('layouts.app');
    }
}
