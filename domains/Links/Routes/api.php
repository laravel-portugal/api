<?php

use Domains\Links\Controllers\LinksIndexController;
use Domains\Links\Controllers\LinksStoreController;

Route::get('/links', LinksIndexController::class)->name('links.index');
Route::post('/links', LinksStoreController::class)->name('links.store');
