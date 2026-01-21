<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', function () {
        return Volt::mount('pages::settings.profile');
    })->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('settings/password', function () {
        return Volt::mount('pages::settings.password');
    })->name('user-password.edit');

    Route::get('settings/appearance', function () {
        return Volt::mount('pages::settings.appearance');
    })->name('appearance.edit');

    Route::get('settings/two-factor', function () {
        return Volt::mount('pages::settings.two-factor');
    })
    ->middleware(
        when(
            Features::canManageTwoFactorAuthentication()
            && Features::optionEnabled(
                Features::twoFactorAuthentication(),
                'confirmPassword'
            ),
            ['password.confirm'],
            [],
        )
    )
    ->name('two-factor.show');
});