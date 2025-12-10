<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndividualProfileController;
use App\Http\Controllers\CareNoteController;
use App\Http\Controllers\MoodCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Individual Profiles
    Route::resource('individuals', IndividualProfileController::class);

    // Care Notes
    Route::post('individuals/{individual}/care-notes', [CareNoteController::class, 'store'])->name('care-notes.store');

    // Mood Checks (CarePulse)
    Route::post('individuals/{individual}/mood-checks', [MoodCheckController::class, 'store'])->name('mood-checks.store');
});

require __DIR__ . '/auth.php';
