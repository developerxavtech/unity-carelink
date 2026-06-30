<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    // Auth routes...
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
});
