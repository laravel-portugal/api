<?php

use Domains\Accounts\Controllers\AccountsStoreController;
use Domains\Accounts\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [
    'as' => 'users.store',
    'uses' => AccountsStoreController::class,
]);

Route::get('/users/verify/{id}/{hash}', [
    'as' => 'users.verify',
    'uses' => VerifyEmailController::class,
]);
