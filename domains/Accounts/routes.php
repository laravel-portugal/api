<?php

use Domains\Accounts\Controllers\AccountsStoreController;
use Domains\Accounts\Controllers\VerifyEmailController;
use Domains\Accounts\Controllers\AccountsLoginController;
use Domains\Accounts\Controllers\AccountsLogoutController;
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
    'uses' => AccountsLoginController::class
]);

Route::post('/logout', [
    'as' => 'accounts.logout',
    'uses' => AccountsLogoutController::class
]);

Route::post('/me', [
    'as' => 'accounts.me',
    'uses' => AccountsProfileController::class
]);
