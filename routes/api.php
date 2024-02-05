<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsersController;
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

        // AUTH ENDPOINTS
        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });
});
