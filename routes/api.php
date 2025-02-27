<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChannelController;
use App\Http\Controllers\Api\V1\PackageChannelController;
use App\Http\Controllers\Api\V1\PackageController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // AUTH ENDPOINTS
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

    // AUTHENTICATED ENDPOINTS
    Route::group(['middleware' => ['auth:sanctum'], 'as' => 'sanctum.'], function () {

        // USER ENDPOINTS
        Route::apiResource('users', UsersController::class);

        // CHANNELS ENDPOINTS
        Route::apiResource('channels', ChannelController::class);

        // PACKAGE ENDPOINTS
        Route::apiResource('packages', PackageController::class);

        // PACKAGE // CHANNELS ENDPOINTS
        Route::post('/packages/{package}/channels/{channel}', [PackageChannelController::class, 'attach'])->name('attach');
        Route::delete('/packages/{package}/channels/{channel}', [PackageChannelController::class, 'detach'])->name('detach');

        // SUBSCRIPTION ENDPOINTS
        Route::apiResource('subscriptions', SubscriptionController::class);

        // AUTH ENDPOINTS
        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });
});
