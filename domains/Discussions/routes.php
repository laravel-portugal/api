<?php

use Domains\Discussions\Controllers\QuestionsStoreController;
use Illuminate\Support\Facades\Route;

Route::post('/questions', [
    'as' => 'questions.store',
    'middleware' => 'auth',
    'uses' => QuestionsStoreController::class,
]);
