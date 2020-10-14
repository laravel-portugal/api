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

Route::post('/login', [
    'as' => 'accounts.login',
    'uses' => VerifyEmailController::class,
]);

Route::post('/logout', [
    'as' => 'accounts.logout',
    'uses' => VerifyEmailController::class,
]);

Route::get('/me', [
    'as' => 'accounts.me',
    'uses' => VerifyEmailController::class,
]);


