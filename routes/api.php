<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UsersController;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    // OPEN ENDPOINTS
    // AUTH ENDPOINTS
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

    // AUTHENTICATED ENDPOINTS
    Route::group(['middleware' => ['auth:sanctum'], 'as' => 'sanctum.'], function () {

        // USER ENDPOINTS
        Route::apiResource('users', UsersController::class);

        // CHANNELS ENDPOINTS
        Route::apiResource('channels', ChannelController::class);

        // PACKAGE ENDPOINTS
        Route::apiResource('packages', PackageController::class);

        // SUBSCRIPTION ENDPOINTS
        Route::apiResource('subscriptions', SubscriptionController::class);

        // AUTH ENDPOINTS
        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });
});
