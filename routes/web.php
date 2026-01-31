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
use App\Livewire\Admin\MemberShow;
use App\Http\Controllers\DonationController;

use App\Livewire\Admin\InviteMembers;

use App\Livewire\Admin\ApproveMembers;

use App\Http\Controllers\PaymentsReportController;
use App\Http\Controllers\DependentsReportController;
use App\Http\Controllers\MembersReportController;
use  App\Http\Controllers\SeedFundsReportController;
use App\Http\Controllers\EventsReportController;



use App\Http\Controllers\BeneficiariesReportController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');
Route::view('/contact', 'contact');
// Donate page
// Route::view('/donate', 'donate');
Route::get('/donate', [DonationController::class, 'showDonationForm'])->name('donation.form');
Route::post('/donate', [DonationController::class, 'processDonation'])->name('donation.process');
Route::get('/donation/success', [DonationController::class, 'success'])->name('donation.success');
Route::get('/donation/cancel', [DonationController::class, 'cancel'])->name('donation.cancel');


// Bereavement pages
Route::view('/bereaved-children', 'bereaved-children');
Route::view('/bereaved-parents', 'bereaved-parents');
Route::view('/bereaved-siblings', 'bereaved-siblings');
Route::view('/bereaved-spouses', 'bereaved-spouses');
Route::view('/what-to-do', 'what-to-do');
Route::view('/terms', 'terms');
Route::view('/privacy-policy', 'privacy-policy');
Route::view('/complaints', 'complaints');
Route::view('/invite-required', 'invite-required')->name('invite-required');


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
Route::middleware(['auth', 'member.approved' ])->group(function () {
    Route::get('/member/dashboard', MemberDashboard::class)->name('member.dashboard');
    Route::get('/profile', function () {return view('profile'); })->name('profile');
});

Route::middleware(['auth', 'member.approved'])->prefix('member')->name('member.')->group(function () {
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
        // Route::get('/show', AdminDashboard::class)->name('show');
        Route::get('/review-events', ReviewEvents::class)->name('review-events');
        Route::get('/review-events/{eventId}', ReviewEvents::class)->name('review-events.detail');


        
        Route::get('/settings', AdminDashboard::class)->name('settings');
        Route::get('/users', ManageCeafUsers::class)->name('users');
        Route::get('/reports', AdminDashboard::class)->name('reports');


        Route::get('/mlist', MembersList::class)->name('members-list');
        Route::get('/active', MembersList::class)->name('active');
        Route::get('/late', MembersList::class)->name('late');
        Route::get('/suspended', MembersList::class)->name('suspended');
        Route::get('/terminated', MembersList::class)->name('terminated');
        Route::get('/show/{member}', MemberShow::class)->name('show');


        Route::get('/invite-members', InviteMembers::class) ->name('invite-members');

        Route::get('/approve-members', ApproveMembers::class)->name('approve-members');
  });



    Route::post('/admin/logout', function () {
            Auth::guard('ceaf')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('admin.login');
        })->name('admin.logout');



Route::middleware(['auth:ceaf'])
    ->prefix('admin/reports')
    ->name('admin.reports.')
    ->group(function () {

        //Payments Reports
        Route::get('/payments', [PaymentsReportController::class, 'index'])->name('payments');
        Route::get('/payments/export', [PaymentsReportController::class, 'export'])->name('payments.export');

        //Memebrs Reports
        Route::get('/members', [MembersReportController::class, 'index'])->name('members');
        Route::get('/members/export', [MembersReportController::class, 'export'])->name('members.export');
        //Evenst Reports
        Route::get('/events', [EventsReportController::class, 'index'])->name('events');
        Route::get('/events/export', [EventsReportController::class, 'export'])->name('events.export');
    
       //Beneficiriries Report
        Route::get('/beneficiaries', [BeneficiariesReportController::class, 'index'])->name('beneficiaries');
        Route::get('/beneficiaries/export', [BeneficiariesReportController::class, 'export']) ->name('beneficiaries.export');
        //Dependendts Report
        Route::get('/dependents', [DependentsReportController::class, 'index']) ->name('dependents');
        Route::get('/dependents/export', [DependentsReportController::class, 'export']) ->name('dependents.export');
        
        //funsds report
        Route::get('/seed-funds', [SeedFundsReportController::class, 'index'])->name('seed_funds');
        Route::get('/seed-funds/export', [SeedFundsReportController::class, 'export'])->name('seed_funds.export');

        });
require __DIR__.'/auth.php';
