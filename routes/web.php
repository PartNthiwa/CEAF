<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Member\Dashboard as MemberDashboard;

use App\Livewire\Member\Payments as MemberPayments;
use App\Livewire\Member\Dependents as MemberDependents;
use App\Livewire\Member\Beneficiaries as MemberBeneficiaries;
use App\Livewire\Member\Profile as MemberProfile;
use App\Livewire\Member\DependentProfile;

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ConfigurationManager;
use App\Livewire\Admin\SeedPaymentCycleManager;

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


Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {


    // Profile
    Route::get('/profile', MemberProfile::class)->name('profile');

    // Payments
    Route::get('/payments', MemberPayments::class)->name('payments');

    // Dependents Management
    Route::get('/dependents', MemberDependents::class)->name('dependents');

    // Beneficiaries Management
    Route::get('/beneficiaries', MemberBeneficiaries::class)->name('beneficiaries');

    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dependent/{dependentId}/profile', DependentProfile::class)
        ->name('dependents.profile');
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
     
        Route::get('/configuration', ConfigurationManager::class)
            ->name('configuration');
        Route::get('/seed-cycle',SeedPaymentCycleManager::class)
            ->name('seed-cycle');

    });

require __DIR__.'/auth.php';
