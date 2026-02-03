<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

$prefix = trim((string) config('site.path', ''), '/');

Route::prefix($prefix)->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/videos', [PageController::class, 'videos'])->name('videos');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/models-draw', [PageController::class, 'models'])->name('models');
    Route::get('/download/model/{filename}', [PageController::class, 'downloadModel'])->name('download.model');

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/videos', [DashboardController::class, 'store'])->name('dashboard.videos.store');
        Route::delete('/dashboard/videos/{video}', [DashboardController::class, 'destroy'])->name('dashboard.videos.destroy');

        Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
        Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
        Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
        Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
        Route::get('/meetings/{meeting}/status', [MeetingController::class, 'status'])->name('meetings.status');
        Route::post('/meetings/{meeting}/end', [MeetingController::class, 'end'])->name('meetings.end');
        Route::get('/meetings/{meeting}/participant-names', [MeetingController::class, 'participantNames'])->name('meetings.participant-names');
        Route::get('/meetings/{meeting}/token', [MeetingController::class, 'token'])->name('meetings.token');
        Route::post('/meetings/{meeting}/feedback', [MeetingController::class, 'storeFeedback'])->name('meetings.feedback');
        Route::get('/meetings/{meeting}/inviteable-users', [MeetingController::class, 'inviteableUsers'])->name('meetings.inviteable-users');
        Route::post('/meetings/{meeting}/invite', [MeetingController::class, 'invite'])->name('meetings.invite');
        Route::post('/meetings/{meeting}/invitations/accept', [MeetingController::class, 'acceptInvitation'])->name('meetings.invitations.accept');
        Route::post('/meetings/{meeting}/invitations/reject', [MeetingController::class, 'rejectInvitation'])->name('meetings.invitations.reject');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
