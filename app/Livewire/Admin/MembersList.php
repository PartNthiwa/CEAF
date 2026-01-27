<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Member;
use App\Models\User;

class MembersList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $showForm = false;
    public $isEdit = false;
    public $memberId;

    public $name;
    public $email;
    public $role;
    public $membership_status;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'role' => 'required|string',
        'membership_status' => 'required|string',
    ];

    public function render()
    {
        $members = Member::with('user')
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
                $q->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, fn($q) => $q->where('membership_status', $this->status))
            ->paginate(20);

        return view('livewire.admin.members-list', compact('members'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEdit = false;
    }

    public function edit($id)
    {
        $this->resetForm();

        $this->isEdit = true;
        $this->memberId = $id;

        $member = Member::with('user')->findOrFail($id);

        $this->name = $member->user->name;
        $this->email = $member->user->email;
        $this->role = $member->role;
        $this->membership_status = $member->membership_status;

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $member = Member::findOrFail($this->memberId);

            // update user
            $member->user()->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            // update member
            $member->update([
                'role' => $this->role,
                'membership_status' => $this->membership_status,
            ]);

            session()->flash('success', 'Member updated successfully.');
        } else {
            // create user + member
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt('password123'), // change to your default
            ]);

            Member::create([
                'user_id' => $user->id,
                'role' => $this->role,
                'meambership_status' => $this->membership_status,
            ]);

            session()->flash('success', 'Member created successfully.');
        }

        $this->showForm = false;
    }

    public function delete($id)
    {
        Member::findOrFail($id)->delete();
        session()->flash('success', 'Member deleted successfully.');
    }

    private function resetForm()
    {
        $this->name = null;
        $this->email = null;
        $this->role = null;
        $this->membership_status = null;
        $this->memberId = null;
    }
}
