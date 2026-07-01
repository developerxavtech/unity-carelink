<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('verify-code', 'verifyCode');
    Route::post('resend-code', 'resendVerificationCode');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
});
Route::middleware('auth:api')->group(function () {
    Route::resource('appointments', AppointmentController::class);
    Route::prefix('dsp')->group(function () {});
});
