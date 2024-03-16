<?php

use App\Actions\SpinWheelAction;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\RankGroupController;
use Illuminate\Support\Facades\Route;

/** Authentication Routes */
Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

/** User Routes */
Route::middleware(['auth:sanctum', 'type.user'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);;
    Route::post('/spin-wheel', SpinWheelAction::class);
});

/** Admin Routes */
Route::middleware(['auth:sanctum', 'type.admin'])->group(function () {
    Route::apiResource('/prizes', PrizeController::class);
    Route::patch('/rank-groups/{rankGroup}/ranks', [RankGroupController::class, 'chooseRanks']);
    Route::post('/rank-groups/{rankGroup}/prizes', [RankGroupController::class, 'assignPrize']);
    Route::apiResource('/rank-groups', RankGroupController::class);
});
