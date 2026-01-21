<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Member\Dashboard as MemberDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ConfigurationManager;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');
/*
|--------------------------------------------------------------------------
| Authenticated Entry Point
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('member.dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/member/dashboard', MemberDashboard::class)
        ->name('member.dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)
            ->name('dashboard');
    });
     Route::get('/configuration', ConfigurationManager::class)
        ->name('admin.configuration');

require __DIR__.'/auth.php';
