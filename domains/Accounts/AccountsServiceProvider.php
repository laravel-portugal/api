<?php

namespace Domains\Accounts;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Dusterio\LumenPassport\LumenPassport;

class AccountsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        LumenPassport::allowMultipleTokens();
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/Resources/Views', 'accounts');
        $this->loadConfig();
        $this->bootRoutes();
    }

    private function bootRoutes(): void
    {
        Route::group(
            [
                'prefix' => 'accounts',
                'as' => 'accounts',
            ],
            fn () => $this->loadRoutesFrom(__DIR__ . '/routes.php')
        );
    }

    private function loadConfig(): void
    {
        $this->app->configure('auth');
        $this->app->configure('accounts');
    }
}
