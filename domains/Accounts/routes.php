<?php

use Domains\Accounts\Controllers\AccountsStoreController;
use Domains\Accounts\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/accounts', [
    'as' => 'accounts.store',
    'uses' => AccountsStoreController::class,
]);

Route::get('/accounts/verify/{id}/{hash}', [
    'as' => 'verification.verify',
    'uses' => VerifyEmailController::class,
]);
