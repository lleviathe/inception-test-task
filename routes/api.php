<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\RankGroupController;
use App\Http\Controllers\WheelController;
use Illuminate\Support\Facades\Route;

/** Authentication Routes */
Route::middleware(['guest'])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

/** User Routes */
Route::middleware(['auth:sanctum', 'type.user'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::controller(WheelController::class)->prefix('wheel')->group(function () {
        Route::post('spin', 'spin')->middleware('throttle:wheel-spin');
        Route::get('prizes', 'getPossiblePrizes');
    });
});

/** Admin Routes */
Route::middleware(['auth:sanctum', 'type.admin'])->group(function () {
    Route::apiResource('prizes', PrizeController::class);

    Route::controller(RankGroupController::class)->prefix('rank-groups')->group(function () {
        Route::patch('{rankGroup}/ranks', 'chooseRanks');
        Route::post('{rankGroup}/prizes', 'assignPrize');
    });

    Route::apiResource('rank-groups', RankGroupController::class);
});
