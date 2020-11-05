<?php

namespace Domains\Discussions;

use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Domains\Discussions\Observers\QuestionObserver;
use Domains\Discussions\Policies\AnswerPolicy;
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
        Gate::policy(Answer::class, AnswerPolicy::class);
    }
}
