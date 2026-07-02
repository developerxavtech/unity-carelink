<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DspController;
use App\Http\Controllers\Api\StatusController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('verify-code', 'verifyCode');
    Route::post('resend-code', 'resendVerificationCode');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
});
Route::get('activity-statuses', [StatusController::class, 'getActivityStatuses']);
Route::middleware('auth:api')->group(function () {
    Route::resource('appointments', AppointmentController::class);

    Route::prefix('dsp')->name('api.dsp.')->group(function () {
        Route::get('clients', [DspController::class, 'clientsList'])->name('clients.index');
        Route::get('clients/search', [DspController::class, 'searchClients'])->name('clients.search');
    });

    // Status / Mood routes
    Route::prefix('status')->name('api.status.')->group(function () {
        Route::get('/', [StatusController::class, 'getCurrentStatus'])->name('current');
        Route::post('/update', [StatusController::class, 'updateStatus'])->name('update');
        Route::post('/clear', [StatusController::class, 'clearStatus'])->name('clear');
    });
});
