<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isSuperAdmin', function($user) {
            return strtolower($user->usertype->name) == 'super admin';
        });

        Gate::define('isSuperOrAdmin', function($user) {
            return strtolower($user->usertype->name) == 'admin' || strtolower($user->usertype->name) == 'super admin';
        });

        Gate::define('isAdmin', function($user) {
            return strtolower($user->usertype->name) == 'admin';
        });

        Gate::define('isOwnerProduct', function($user) {
            return strtolower($user->usertype->name) == 'owner product';
        });

        Gate::define('isCustomer', function($user) {
            return strtolower($user->usertype->name) == 'customer';
        });


        Passport::routes();
    }
}
