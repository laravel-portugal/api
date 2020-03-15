<?php

use Domains\Tags\Controllers\TagsIndexController;

Route::get('/tags', TagsIndexController::class)->name('tags.index');
