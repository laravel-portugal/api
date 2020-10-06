<?php

namespace Domains\Accounts;

use Domains\Accounts\Models\User;
use Domains\Accounts\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AccountsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/Resources/Views', 'accounts');
        $this->loadConfig();
        $this->bootRoutes();
        $this->registerPolicies();
    }

    private function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    private function loadConfig(): void
    {
        $this->app->configure('accounts');
    }

    private function registerPolicies()
    {
        Gate::policy(User::class, UserPolicy::class);
    }
}
