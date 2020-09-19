<?php

use Domains\Links\Controllers\LinksIndexController;
use Domains\Links\Controllers\LinksStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/links', [
    'as' => 'links.index',
    'uses' => LinksIndexController::class,
]);

Route::post('/links', [
    'as' => 'links.store',
    'uses' => LinksStoreController::class,
]);
