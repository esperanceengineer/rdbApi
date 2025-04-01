<?php

use App\Enums\GLOBAL_ROLE;
use App\Http\Controllers\Api\SecurityController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AlwaysAcceptsJson;
use Illuminate\Support\Facades\Route;

Route::post('/login', [SecurityController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:api', AlwaysAcceptsJson::class])->prefix('auth')->group(function () {
    Route::post('/logout', [SecurityController::class, 'logout']);

    Route::get('candidates', [StatisticController::class, 'candidates']);
    Route::get('provincies', [StatisticController::class, 'provincies']);
    Route::get('departments', [StatisticController::class, 'departments']);
    Route::get('localities', [StatisticController::class, 'localities']);
    Route::get('centers', [StatisticController::class, 'centers']);

    Route::middleware('can:' . GLOBAL_ROLE::ADMIN->value)->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/lock', [UserController::class, 'lock']);
        Route::post('users/{user}/changePassword', [UserController::class, 'changePassword']);
        Route::post('users/{user}/resetPassword', [UserController::class, 'resetPassword']);
    });

    Route::middleware('can:' . GLOBAL_ROLE::CONSULTANT->value)->prefix('consultant')->group(function () {});

    Route::middleware('can:' . GLOBAL_ROLE::REPRESENTANT->value)->prefix('representant')->group(function () {
        Route::post('results', [StatisticController::class, 'storeResult']);
    });
});
