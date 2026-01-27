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
use App\Livewire\Admin\PaymentsManager;
use App\Livewire\Admin\DependentsManager;
use App\Livewire\Admin\BeneficiariesManager;
use App\Livewire\Admin\SeedPaymentCycleManager;
use App\Livewire\Admin\BeneficiaryRequests;
use App\Livewire\Member\SubmitEvent;
use App\Livewire\Admin\ReviewEvents;
use App\Livewire\Member\EventHistory ;
use App\Http\Controllers\PayPalController;
use App\Livewire\Admin\ManageCeafUsers;
use App\Models\Ceaf;
use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\MembersList;
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

Route::get('/admin/login', Login::class)->name('admin.login');

Route::middleware(['auth'])->get('/dashboard', function () {

    $user = auth()->user();

    $ceafUser = Ceaf::where('email', $user->email)->first();

    if ($ceafUser && $ceafUser->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('member.dashboard');

})->name('dashboard');
/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth' ])->group(function () {
    Route::get('/member/dashboard', MemberDashboard::class)->name('member.dashboard');
    Route::get('/profile', function () {return view('profile'); })->name('profile');
});

Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {
    Route::get('/profile', MemberProfile::class)->name('profile');
    Route::get('/payments', MemberPayments::class)->name('payments');
    Route::get('/dependents', MemberDependents::class)->name('dependents');
    Route::get('/beneficiaries', MemberBeneficiaries::class)->name('beneficiaries');

    Route::get('/submit-event', SubmitEvent::class)->name('submit-event');
    Route::get('/event-history', EventHistory::class)->name('event-history');

    Route::get('/paypal/success/{payment}',[PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel/{payment}',[PayPalController::class, 'cancel'])->name('paypal.cancel');

});

Route::middleware(['auth', ])->group(function () {
    Route::get('/dependent/{dependentId}/profile', DependentProfile::class)
        ->name('dependents.profile');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:ceaf'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');     
        Route::get('/configuration', ConfigurationManager::class)->name('configuration');
        Route::get('/seed-cycle',SeedPaymentCycleManager::class)->name('seed-cycle');    
        Route::get('/beneficiary-requests', BeneficiaryRequests::class)->name('beneficiary.requests');
        Route::get('/payments', PaymentsManager::class)->name('payments');
        Route::get('/dependents', DependentsManager::class)->name('dependents');
        Route::get('/beneficiaries', BeneficiariesManager::class)->name('beneficiaries');
        Route::get('/show', AdminDashboard::class)->name('show');
        Route::get('/review-events', ReviewEvents::class)->name('review-events');
        Route::get('/review-events/{eventId}', ReviewEvents::class)->name('review-events.detail');


        
        Route::get('/settings', AdminDashboard::class)->name('settings');
        Route::get('/users', ManageCeafUsers::class)->name('manage-ceaf-users');
        Route::get('/reports', AdminDashboard::class)->name('reports');


        Route::get('/mlist', MembersList::class)->name('members-list');

        });

    Route::post('/admin/logout', function () {
    Auth::guard('ceaf')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('admin.login');
})->name('admin.logout');


require __DIR__.'/auth.php';
