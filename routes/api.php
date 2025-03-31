<?php

use App\Enums\GLOBAL_ROLE;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AlwaysAcceptsJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'throttle:api', AlwaysAcceptsJson::class])->prefix('auth')->group(function () {

    Route::middleware('can:'.GLOBAL_ROLE::ADMIN->value)->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/lock', [UserController::class, 'lock']);
        Route::post('users/{user}/changePassword', [UserController::class, 'changePassword']);
        Route::post('users/{user}/resetPassword', [UserController::class, 'resetPassword']);

    });

    Route::middleware('can:'.GLOBAL_ROLE::CONSULTANT->value)->prefix('consultant')->group(function () {
        
    });

    Route::middleware('can:'.GLOBAL_ROLE::REPRESANTANT->value)->prefix('representant')->group(function () {
        
    });
});

