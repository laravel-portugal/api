<?php

use Domains\Tags\Controllers\TagsIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/tags', [
    'as' => 'tags.index',
    'uses' => TagsIndexController::class,
]);
