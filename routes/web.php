<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyDashboardController;
use App\Http\Controllers\DspDashboardController;
use App\Http\Controllers\ProgramDashboardController;
use App\Http\Controllers\AgencyDashboardController;
use App\Http\Controllers\IndividualProfileController;
use App\Http\Controllers\CareNoteController;
use App\Http\Controllers\MoodCheckController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\FamilyMemberController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Family Dashboard Routes
Route::middleware(['auth', 'verified'])->prefix('family')->name('family.')->group(function () {
    Route::get('/home', [FamilyDashboardController::class, 'home'])->name('home');
    Route::get('/daily-updates', [FamilyDashboardController::class, 'dailyUpdates'])->name('daily-updates');
    Route::get('/calendar', [FamilyDashboardController::class, 'calendar'])->name('calendar');
    Route::get('/dsp-notes', [FamilyDashboardController::class, 'dspNotes'])->name('dsp-notes');
    Route::get('/program-updates', [FamilyDashboardController::class, 'programUpdates'])->name('program-updates');
    Route::get('/rides', [FamilyDashboardController::class, 'rides'])->name('rides');
    Route::get('/messages', [FamilyDashboardController::class, 'messages'])->name('messages');
    Route::get('/messages/{conversation}', [FamilyDashboardController::class, 'conversation'])->name('messages.show');
    Route::get('/resources', [FamilyDashboardController::class, 'resources'])->name('resources');

    // Status Routes
    Route::get('/status/edit', [UserStatusController::class, 'edit'])->name('status.edit');
    Route::post('/status/update', [UserStatusController::class, 'update'])->name('status.update');
    Route::post('/status/clear', [UserStatusController::class, 'clear'])->name('status.clear');

    // Family Member Management
    Route::resource('members', FamilyMemberController::class);
});

// Chat Routes
Route::middleware(['auth', 'verified'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/create', [ChatController::class, 'create'])->name('create');
    Route::post('/', [ChatController::class, 'store'])->name('store');
    Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
    Route::post('/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('messages.send');
    Route::post('/{conversation}/participants', [ChatController::class, 'addParticipant'])->name('participants.add');
});


// DSP Dashboard Routes
Route::middleware(['auth', 'verified'])->prefix('dsp')->name('dsp.')->group(function () {
    Route::get('/home', [DspDashboardController::class, 'home'])->name('home');
    Route::get('/participants', [DspDashboardController::class, 'participants'])->name('participants');
    Route::get('/daily-logs', [DspDashboardController::class, 'dailyLogs'])->name('daily-logs');
    Route::get('/skill-tracking', [DspDashboardController::class, 'skillTracking'])->name('skill-tracking');
    Route::get('/rides', [DspDashboardController::class, 'rides'])->name('rides');
    Route::get('/peer-support', [DspDashboardController::class, 'peerSupport'])->name('peer-support');
    Route::get('/messages', [DspDashboardController::class, 'messages'])->name('messages');
    Route::get('/messages/{conversation}', [DspDashboardController::class, 'conversation'])->name('messages.show');
    Route::get('/time-tracking', [DspDashboardController::class, 'timeTracking'])->name('time-tracking');
    Route::get('/calendar', [DspDashboardController::class, 'calendar'])->name('calendar');

    // Status Routes
    Route::get('/status/edit', [UserStatusController::class, 'edit'])->name('status.edit');
    Route::post('/status/update', [UserStatusController::class, 'update'])->name('status.update');
    Route::post('/status/clear', [UserStatusController::class, 'clear'])->name('status.clear');
});

// Program Dashboard Routes
Route::middleware(['auth', 'verified'])->prefix('program')->name('program.')->group(function () {
    Route::get('/home', [ProgramDashboardController::class, 'home'])->name('home');
    Route::get('/attendance', [ProgramDashboardController::class, 'attendance'])->name('attendance');
    Route::get('/family-updates', [ProgramDashboardController::class, 'familyUpdates'])->name('family-updates');
    Route::get('/skill-tracking', [ProgramDashboardController::class, 'skillTracking'])->name('skill-tracking');
    Route::get('/spot-availability', [ProgramDashboardController::class, 'spotAvailability'])->name('spot-availability');
    Route::get('/messages', [ProgramDashboardController::class, 'messages'])->name('messages');
    Route::get('/messages/{conversation}', [ProgramDashboardController::class, 'conversation'])->name('messages.show');
});

// Agency Dashboard Routes
Route::middleware(['auth', 'verified'])->prefix('agency')->name('agency.')->group(function () {
    Route::get('/home', [AgencyDashboardController::class, 'home'])->name('home');
    Route::get('/staffing', [AgencyDashboardController::class, 'staffing'])->name('staffing');
    Route::get('/compliance-alerts', [AgencyDashboardController::class, 'complianceAlerts'])->name('compliance-alerts');
    Route::get('/incident-reports', [AgencyDashboardController::class, 'incidentReports'])->name('incident-reports');
    Route::get('/program-utilization', [AgencyDashboardController::class, 'programUtilization'])->name('program-utilization');
    Route::get('/team-communication', [AgencyDashboardController::class, 'teamCommunication'])->name('team-communication');
    Route::get('/team-communication/{conversation}', [AgencyDashboardController::class, 'conversation'])->name('team-communication.show');
    Route::get('/billing-payroll', [AgencyDashboardController::class, 'billingPayroll'])->name('billing-payroll');
    Route::get('/calendar', [AgencyDashboardController::class, 'calendar'])->name('calendar');
});

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

    // Calendar Events (API endpoints for all authenticated users)
    Route::resource('calendar-events', CalendarEventController::class)->except(['create', 'edit']);
});

require __DIR__ . '/auth.php';
