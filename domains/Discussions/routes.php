<?php

use Domains\Discussions\Controllers\AnswersStoreController;
use Domains\Discussions\Controllers\QuestionsStoreController;
use Domains\Discussions\Controllers\QuestionsUpdateController;
use Domains\Discussions\Controllers\QuestionsViewController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::post('/questions', [
        'as' => 'questions.store',
        'uses' => QuestionsStoreController::class,
    ]);

    Route::patch('/questions/{questionId}', [
        'as' => 'questions.update',
        'uses' => QuestionsUpdateController::class,
    ]);

    Route::post('/questions/{questionId}/answers', [
        'as' => 'questions.answers',
        'uses' => AnswersStoreController::class
    ]);
});

Route::get('questions/{questionId}', [
    'as' => 'questions.view',
    'uses' => QuestionsViewController::class
]);
