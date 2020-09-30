<?php

namespace Domains\Links;

use Domains\Links\Models\Link;
use Domains\Links\Observers\LinkObserver;
use Illuminate\Support\ServiceProvider;

class LinksServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadConfig();

        $this->bootRoutes();
        $this->bootObservers();
    }

    private function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    private function bootObservers(): void
    {
        Link::observe(LinkObserver::class);
    }

    private function loadConfig(): void
    {
        $this->app->configure('links');
    }
}
