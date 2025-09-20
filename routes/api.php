<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

// keep your existing /login, /logout, /me
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/staff',              [StaffController::class, 'index'])->middleware('can:view users');
    Route::get('/staff/{id}',         [StaffController::class, 'show'])->middleware('can:view users');

    Route::post('/staff',             [StaffController::class, 'store'])->middleware('can:create users');
    Route::patch('/staff/{id}',       [StaffController::class, 'update'])->middleware('can:edit users');

    Route::patch('/staff/{id}/manager',[StaffController::class, 'assignManager'])->middleware('can:manage reporting lines');
});
