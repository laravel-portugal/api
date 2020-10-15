<?php

use Domains\Accounts\Controllers\AccountsStoreController;
use Domains\Accounts\Controllers\VerifyEmailController;
use Domains\Accounts\Controllers\AccountsLoginController;
use Domains\Accounts\Controllers\AccountsLogoutController;
use Domains\Accounts\Controllers\AccountsProfileController;
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
    'as' => 'login',
    'uses' => AccountsLoginController::class,
    'middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:10,1'
]);

Route::post('/logout', [
    'as' => 'logout',
    'uses' => AccountsLogoutController::class,
    'middleware' => 'auth:api'
]);

Route::get('/me', [
    'as' => 'me',
    'uses' => AccountsProfileController::class,
    'middleware' => 'auth:api'
]);
