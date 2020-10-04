<?php

use Domains\Accounts\Controllers\AccountsStoreController;
use Domains\Accounts\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

//Route::group(['middleware' => 'guest'], function () {
    Route::post('/accounts', [
        'as' => 'accounts.store',
        'uses' => AccountsStoreController::class,
    ]);
    Route::get('/accounts/verify/{id}/{hash}', [
        'as' => 'accounts.verify',
        'uses' => VerifyEmailController::class,
    ]);
//});

