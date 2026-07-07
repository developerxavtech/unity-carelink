<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DailyLogController;
use App\Http\Controllers\Api\DspController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\VoiceCornerController;
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

        Route::apiResource('daily-logs', DailyLogController::class);
    });
    Route::prefix('family')->name('api.family.')->group(function () {
        Route::get('members', [FamilyController::class, 'members']);

    });

    // Status / Mood routes
    Route::prefix('status')->name('api.status.')->group(function () {
        Route::get('/', [StatusController::class, 'getCurrentStatus'])->name('current');
        Route::post('/update', [StatusController::class, 'updateStatus'])->name('update');
        Route::post('/clear', [StatusController::class, 'clearStatus'])->name('clear');
    });

    Route::prefix('chat')->name('api.chat.')->group(function () {
        Route::get('contact-list', [ChatController::class, 'contactList']);

        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/', [ChatController::class, 'store'])->name('store');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('messages.send');
        Route::post('/{conversation}/participants', [ChatController::class, 'addParticipant'])->name('participants.add');
    });

    // Voice Corner - DSP-only community feed (real-time via Pusher private
    // channel "voice-corner", events "post.created" and "reaction.updated")
    Route::prefix('voice-corner')->name('api.voice-corner.')->group(function () {
        Route::get('/', [VoiceCornerController::class, 'index'])->name('index');
        Route::post('/', [VoiceCornerController::class, 'store'])->name('store');
        Route::post('/{post}/reactions', [VoiceCornerController::class, 'react'])->name('reactions.react');
    });
});
