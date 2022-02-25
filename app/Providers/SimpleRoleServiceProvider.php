<?php

namespace App\Providers;

use App\Models\Role;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SimpleRoleServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('user', 'App\Policies\RolePolicy@checkRole');
		
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}