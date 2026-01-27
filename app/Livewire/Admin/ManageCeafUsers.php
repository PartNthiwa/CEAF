<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Ceaf;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManageCeafUsers extends Component
{
    use WithPagination;

    public $name, $email, $role, $password, $confirmPassword, $userId;
    public $updateMode = false;
    public $roles = []; 
    public $passwordVisible = false; 
    public $confirmPasswordVisible = false;
   
    public function render()
    {
        $users = Ceaf::paginate(10);
        $this->roles = User::select('role')->distinct()->pluck('role'); 
        return view('livewire.admin.manage-ceaf-users', compact('users'))->layout('layouts.admin');
    }

    // Toggle the visibility of the password
    public function togglePasswordVisibility()
    {
        $this->passwordVisible = !$this->passwordVisible;
    }

    public function toggleConfirmPasswordVisibility()
    {
        $this->confirmPasswordVisible = !$this->confirmPasswordVisible;
    }

    // Store new user
    public function store()
    {
        // Validate data
        $this->validateData();

        // Create new Ceaf user
        Ceaf::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => bcrypt($this->password), 
        ]);

        session()->flash('success', 'User successfully created!');
        $this->resetInputFields();
    }

    // Edit user data
    public function edit($userId)
    {
        $this->updateMode = true;
        $user = Ceaf::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    // Update user
    public function update()
    {
        // Validate data
        $this->validateData();

        $user = Ceaf::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => bcrypt($this->password), 
        ]);

        session()->flash('success', 'User successfully updated!');
        $this->resetInputFields();
    }

    // Delete user
    public function delete($userId)
    {
        Ceaf::find($userId)->delete();
        session()->flash('success', 'User successfully deleted!');
    }

    // Validation method
    private function validateData()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:ceaf,email,' . ($this->userId ?? 'NULL'),
            'role' => 'required|string|max:255',
            'password' => 'required|string|min:8', 
            'confirmPassword' => 'required|same:password', 
        ];

        $this->validate($rules);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->password = '';
        $this->confirmPassword = '';
        $this->userId = null;
        $this->updateMode = false;
    }
}
