<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberInvitationMail;

class InviteMembers extends Component
{
    public string $email = '';

    protected array $rules = [
        'email' => 'required|email',
    ];

    public function sendInvite(): void
    {
        $this->validate();

        // Prevent multiple unused invites to same email
        $existing = Invitation::where('email', $this->email)
            ->whereNull('used_at')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($existing) {
            $this->addError('email', 'An active invitation already exists for this email.');
            return;
        }

        $invitation = Invitation::create([
            'email' => strtolower($this->email),
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
            'invited_by' => auth()->id(),
        ]);

        Mail::to($invitation->email)->send(new MemberInvitationMail($invitation));

        $this->reset('email');
        session()->flash('success', 'Invitation sent successfully.');
    }

    public function render()
    {
        $invites = Invitation::latest()->take(20)->get();

        return view('livewire.admin.invite-members', [
            'invites' => $invites,
        ])->layout('layouts.admin');
    }
}
