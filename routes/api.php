<?php

use App\Actions\SpinWheelAction;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/** Authentication Routes */
Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

/** User Routes */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

;    Route::post('/spin-wheel', SpinWheelAction::class);
});

/** Admin Routes */
Route::middleware(['auth:admin'])->group(function () {
    Route::post('/prizes/assign', [PrizeController::class, 'assignToRankGroup']);
    Route::apiResource('/prizes', PrizeController::class);
});
