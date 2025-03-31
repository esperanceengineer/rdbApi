<?php

use App\Enums\GLOBAL_ROLE;
use App\Http\Middleware\AlwaysAcceptsJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'throttle:api', AlwaysAcceptsJson::class])->prefix('auth')->group(function () {

    Route::middleware('can:'.GLOBAL_ROLE::CONSULTANT->value)->prefix('consultant')->group(function () {
        
    });

    Route::middleware('can:'.GLOBAL_ROLE::REPRESANTANT->value)->prefix('representant')->group(function () {
        
    });
});

