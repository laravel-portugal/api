<?php

use Domains\Discussions\Controllers\AnswersStoreController;
use Domains\Discussions\Controllers\QuestionsStoreController;
use Illuminate\Support\Facades\Route;

Route::post('/questions', [
    'as' => 'questions.store',
    'middleware' => 'auth',
    'uses' => QuestionsStoreController::class,
]);

Route::post('/questions/{questionId}/answers', [
    'as' => 'questions.answers',
    'middleware' => 'auth',
    'uses' => AnswersStoreController::class,
]);

