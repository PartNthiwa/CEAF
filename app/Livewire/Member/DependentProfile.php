<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Dependent;
use App\Models\DependentDocument;
use Illuminate\Support\Facades\Storage;

class DependentProfile extends Component
{

    use WithFileUploads;

    public $dependentId;
    public $dependent;
    public $documentType;
    public $file;
    public $uploadedDocuments = [];

    public function mount($dependentId)
    {
        $this->dependentId = $dependentId;
        $this->dependent = auth()->user()->member
            ->dependents()
            ->where('id', $dependentId)
            ->firstOrFail();

        $this->loadDocuments();
    }

    public function loadDocuments()
    {
        $this->uploadedDocuments = $this->dependent->documents()->get();
    }

    public function uploadDocument()
    {
        $this->validate([
            'documentType' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = $this->file->store('dependents', 'public');

        DependentDocument::create([
            'dependent_id' => $this->dependent->id,
            'type' => $this->documentType,
            'file_path' => $path,
            'approved' => false,
        ]);

        $this->file = null;
        $this->documentType = null;
        $this->loadDocuments();

        session()->flash('message', 'Document uploaded successfully.');
    }



    public function markProfileComplete()
    {
        // Fetch the dependent via the authenticated user’s member
        $dependent = auth()->user()->member
            ->dependents()
            ->where('id', $this->dependentId)
            ->first();

        if (!$dependent) {
            session()->flash('error', 'Dependent not found or unauthorized.');
            return;
        }

        // Ensure at least one document exists
        if ($dependent->documents()->count() < 1) {
            session()->flash('error', 'Upload at least one document before completing the profile.');
            return;
        }

        // dd($dependent->toArray());
        // Update the profile_completed flag
        $updated = $dependent->update(['profile_completed' => true]);

        if ($updated) {
            // Refresh property so UI updates
            $this->dependent = $dependent->fresh();
            session()->flash('message', 'Dependent profile completed successfully.');
        } else {
            session()->flash('error', 'Failed to mark profile as complete. Try again.');
        }
    }


    public function render()
    {
        return view('livewire.member.dependent-profile')
           ->layout('layouts.app');
    }
}
