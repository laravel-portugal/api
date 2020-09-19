<?php

namespace Domains\Tags;

use Illuminate\Support\ServiceProvider;

class TagsServiceProvider extends ServiceProvider
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
