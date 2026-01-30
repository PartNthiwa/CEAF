<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class ApproveMembers extends Component
{
    public function approve(int $memberId)
    {
        DB::transaction(function () use ($memberId) {
            $member = Member::lockForUpdate()->findOrFail($memberId);

            if ($member->approved_at) return;

            $nextNumber = Member::whereNotNull('member_number')->count() + 1;
            $memberNumber = 'CEAF-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            $member->update([
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'membership_status' => 'active',
                'member_number' => $memberNumber,
            ]);
        });

        session()->flash('success', 'Member approved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.approve-members', [
            'members' => Member::whereNull('approved_at')->with('user')->get(),
        ])->layout('layouts.admin');
    }
}
