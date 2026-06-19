<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PlayerRegistrationController;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('player')->group(function () {
    Route::post('/register', [PlayerRegistrationController::class, 'register'])
        ->middleware('throttle:5,1');
        
    // Protected Player API routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/heartbeat', [\App\Http\Controllers\Api\PlayerApiController::class, 'heartbeat']);
        Route::get('/sync', [\App\Http\Controllers\Api\PlayerApiController::class, 'sync']);
        Route::get('/media/{id}', [\App\Http\Controllers\Api\PlayerApiController::class, 'media']);
        Route::post('/status', [\App\Http\Controllers\Api\PlayerApiController::class, 'status']);
        Route::post('/analytics', [\App\Http\Controllers\Api\PlayerApiController::class, 'analytics']);
    });
});
