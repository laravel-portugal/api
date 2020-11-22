<?php

use Domains\Discussions\Controllers\AnswersStoreController;
use Domains\Discussions\Controllers\AnswersIndexController;
use Domains\Discussions\Controllers\QuestionsDeleteController;
use Domains\Discussions\Controllers\QuestionsIndexController;
use Domains\Discussions\Controllers\AnswersUpdateController;
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

    Route::delete('/questions/{questionId}', [
        'as' => 'questions.delete',
        'uses' => QuestionsDeleteController::class,
    ]);

    Route::post('/questions/{questionId}/answers', [
        'as' => 'questions.answers',
        'uses' => AnswersStoreController::class,
    ]);

    Route::patch('questions/{questionId}/answers/{answerId}', [
        'as' => 'questions.answers.update',
        'uses' => AnswersUpdateController::class,
    ]);
});

Route::get('questions', [
    'as' => 'questions.index',
    'uses' => QuestionsIndexController::class,
]);

Route::get('questions/{questionId}', [
    'as' => 'questions.view',
    'uses' => QuestionsViewController::class,
]);

Route::get('/questions/{questionId}/answers', [
    'as' => 'questions.answers.list',
    'uses' => AnswersIndexController::class
]);
