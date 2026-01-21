<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Member;
use App\Models\Event;
use App\Models\Payment;

use App\Policies\MemberPolicy;
use App\Policies\EventPolicy;
use App\Policies\PaymentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Member::class => MemberPolicy::class,
        Event::class => EventPolicy::class,
        Payment::class => PaymentPolicy::class,
        // add other models/policies if needed
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Optional: extra gates if needed
        Gate::define('admin-only', fn ($user) => $user->isAdmin());
    }
}
