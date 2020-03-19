<?php

use Domains\Links\Controllers\LinksStoreController;

Route::post('/links', LinksStoreController::class)->name('links.store');
