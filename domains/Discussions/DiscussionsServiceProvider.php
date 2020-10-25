<?php

namespace Domains\Discussions;

use Domains\Accounts\Middleware\ThrottleGuestMiddleware;
use Domains\Discussions\Models\Question;
use Domains\Discussions\Observers\QuestionObserver;
use Domains\Discussions\Policies\QuestionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DiscussionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->bootRoutes();
        $this->bootObservers();
        $this->bootPolicies();
        $this->routeMiddleware();
    }

    private function bootRoutes(): void
    {
        Route::group(
            [
                'prefix' => 'discussions',
                'as' => 'discussions',
            ],
            fn() => $this->loadRoutesFrom(__DIR__ . '/routes.php')
        );
    }

    private function bootObservers(): void
    {
        Question::observe(QuestionObserver::class);
    }

    private function bootPolicies(): void
    {
        Gate::policy(Question::class, QuestionPolicy::class);
    }

    private function routeMiddleware(): void
    {
        $this->app->routeMiddleware(
            [
                'throttle_guest' => ThrottleGuestMiddleware::class,
            ]
        );
    }
}
