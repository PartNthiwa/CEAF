<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Member;
use App\Models\User;

class MembersList extends Component
{
    use WithPagination;

    public string $search = '';

    /**
     * Make status a STRING, not array.
     * Because your <select wire:model="status"> sends a single value.
     */
    public ?string $status = null;

    public bool $showForm = false;
    public bool $isEdit = false;

    public ?int $memberId = null;

    public ?string $name = null;
    public ?string $email = null;
    public ?string $role = 'member';
    public ?string $membership_status = null;

    public array $availableStatuses = ['active', 'late', 'suspended', 'terminated'];

    public int $paginationCount = 20;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'membership_status' => 'required|string',
        'role' => 'nullable|string',
    ];

    protected $listeners = ['statusFiltered' => 'updateStatus'];

    public function mount(): void
    {
        // If you prefer to pull statuses from DB later you can.
        // For now we keep a clean default list.
    }

    public function updateStatus($status): void
    {
        // Some code might send array; normalize to string.
        if (is_array($status)) {
            $this->status = $status[0] ?? null;
        } else {
            $this->status = $status ?: null;
        }

        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = trim($this->search);

        $query = Member::query()
            ->with('user')
            ->when($search !== '', function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    // IMPORTANT: group search conditions to avoid OR breaking logic
                    $u->where(function ($x) use ($search) {
                        $x->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            })
            ->when(!empty($this->status), function ($q) {
                $q->where('membership_status', $this->status);
            })
            ->orderByDesc('created_at');

        $members = $query->paginate($this->paginationCount);

        return view('livewire.admin.members-list', compact('members'))
            ->layout('layouts.admin');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showForm = true;
    }

    public function edit($id): void
    {
        $this->resetForm();

        $this->isEdit = true;
        $this->memberId = (int) $id;

        $member = Member::with('user')->findOrFail($id);

        $this->name = $member->user->name;
        $this->email = $member->user->email;
        $this->role = $member->role ?? 'member';
        $this->membership_status = $member->membership_status;

        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $role = $this->role ?: 'member';

        // EDIT MODE: Update existing member + user
        if ($this->isEdit && $this->memberId) {
            $member = Member::with('user')->findOrFail($this->memberId);

            // Prevent email collision with another user
            $emailTaken = User::where('email', $this->email)
                ->where('id', '!=', $member->user_id)
                ->exists();

            if ($emailTaken) {
                session()->flash('error', 'Email is already used by another user.');
                return;
            }

            $member->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $role,
            ]);

            $member->update([
                'role' => $role,
                'membership_status' => $this->membership_status,
            ]);

            session()->flash('success', 'Member updated successfully.');
            $this->showForm = false;
            return;
        }

        // CREATE MODE: create user if missing
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt('Password321?'),
                'role' => $role,
            ]);
        }

        $memberExists = Member::where('user_id', $user->id)->exists();
        if ($memberExists) {
            session()->flash('error', 'This user is already a member.');
            $this->showForm = false;
            return;
        }

        Member::create([
            'user_id' => $user->id,
            'role' => $role,
            'membership_status' => $this->membership_status,
            'join_date' => now(),
        ]);

        session()->flash('success', 'Member created successfully.');
        $this->showForm = false;
    }

    public function delete($id): void
    {
        $member = Member::with('user')->findOrFail($id);

        $user = $member->user;

        $member->delete();

        if ($user) {
            $user->delete();
        }

        session()->flash('success', 'Member and user deleted successfully.');
    }

    private function resetForm(): void
    {
        $this->name = null;
        $this->email = null;
        $this->role = 'member';
        $this->membership_status = null;
        $this->memberId = null;
    }
}
