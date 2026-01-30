<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\Invitation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
public function create(array $input): User
{

\Log::info('CreateNewUser fired', $input);

    Validator::make($input, [
        ...$this->profileRules(),
        'password' => $this->passwordRules(),
        'token' => ['required', 'string'],
    ])->validate();

    return DB::transaction(function () use ($input) {

        $inv = Invitation::where('token', $input['token'])
            ->lockForUpdate()
            ->first();

        if (!$inv) {
            throw ValidationException::withMessages(['token' => 'Invalid invitation token.']);
        }

        if ($inv->used_at) {
            throw ValidationException::withMessages(['token' => 'This invitation has already been used.']);
        }

        if ($inv->expires_at && now()->gt($inv->expires_at)) {
            throw ValidationException::withMessages(['token' => 'This invitation has expired.']);
        }

        if (strtolower($inv->email) !== strtolower($input['email'])) {
            throw ValidationException::withMessages(['email' => 'This invitation is for a different email address.']);
        }
        $inv->update(['used_at' => now()]);
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    });
}
}
