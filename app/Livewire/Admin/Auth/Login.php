<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;


use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('ceaf')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {
            session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        $this->addError('email', 'Invalid admin credentials');
    }

    public function render()
    {
        return view('livewire.admin.auth.login')
            ->layout('layouts.admin-guest');
    }
}
