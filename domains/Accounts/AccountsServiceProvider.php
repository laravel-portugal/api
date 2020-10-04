<?php

namespace Domains\Accounts;

use Illuminate\Support\ServiceProvider;

class AccountsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->bootRoutes();
    }

    private function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
