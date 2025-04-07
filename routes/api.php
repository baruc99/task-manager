<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

Route::post('/register', [RegisteredUserController::class, 'store']);
// Route::post('/login', [AuthenticatedSessionController::class, 'store']);


Route::middleware([StartSession::class])->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    // Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
});
