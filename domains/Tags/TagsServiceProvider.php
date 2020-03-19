<?php

namespace Domains\Tags;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TagsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadFactoriesFrom(__DIR__ . '/Database/Factories');

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        $this->bootRoutes();
    }

    private function bootRoutes(): void
    {
        Route::middleware(['api', 'throttle'])
            ->group(fn () => $this->loadRoutesFrom(__DIR__ . '/Routes/api.php'));
    }
}
