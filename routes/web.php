<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\ContactSettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobAdminController;
use App\Http\Controllers\JobApplicationAdminController;
use App\Http\Controllers\JobOpeningController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCategoryController;
use Illuminate\Support\Facades\Route;

$prefix = trim((string) config('site.path', ''), '/');

Route::prefix($prefix)->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/videos', [PageController::class, 'videos'])->name('videos');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
    Route::get('/models-draw', [PageController::class, 'models'])->name('models');
    Route::get('/jobs', [JobOpeningController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job:slug}', [JobOpeningController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{job:slug}/apply', [JobOpeningController::class, 'apply'])->name('jobs.apply');

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/download/model/track', [PageController::class, 'trackModelDownload'])->name('download.model.track');
        Route::get('/download/model/{path}', [PageController::class, 'downloadModel'])->name('download.model')->where('path', '.*');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/categories', [VideoCategoryController::class, 'index'])->name('dashboard.categories.index');
        Route::post('/dashboard/categories', [VideoCategoryController::class, 'store'])->name('dashboard.categories.store');
        Route::put('/dashboard/categories/reorder', [VideoCategoryController::class, 'reorder'])->name('dashboard.categories.reorder');
        Route::put('/dashboard/categories/{category}', [VideoCategoryController::class, 'update'])->name('dashboard.categories.update');
        Route::delete('/dashboard/categories/{category}', [VideoCategoryController::class, 'destroy'])->name('dashboard.categories.destroy');
        Route::post('/dashboard/videos', [DashboardController::class, 'store'])->name('dashboard.videos.store');
        Route::put('/dashboard/videos/reorder', [DashboardController::class, 'reorderVideos'])->name('dashboard.videos.reorder');
        Route::delete('/dashboard/videos/{video}', [DashboardController::class, 'destroy'])->name('dashboard.videos.destroy');
        Route::get('/dashboard/media', [MediaController::class, 'index'])->name('dashboard.media.index');
        Route::post('/dashboard/media', [MediaController::class, 'store'])->name('dashboard.media.store');
        Route::put('/dashboard/media/{media}', [MediaController::class, 'update'])->name('dashboard.media.update');
        Route::delete('/dashboard/media/{media}', [MediaController::class, 'destroy'])->name('dashboard.media.destroy');
        Route::get('/dashboard/slider', [SliderController::class, 'index'])->name('dashboard.slider.index');
        Route::post('/dashboard/slider', [SliderController::class, 'store'])->name('dashboard.slider.store');
        Route::delete('/dashboard/slider/{slide}', [SliderController::class, 'destroy'])->name('dashboard.slider.destroy');
        Route::get('/dashboard/contact-settings', [ContactSettingController::class, 'edit'])->name('dashboard.contact-settings.edit');
        Route::put('/dashboard/contact-settings', [ContactSettingController::class, 'update'])->name('dashboard.contact-settings.update');
        Route::get('/dashboard/contact-messages', [ContactMessageController::class, 'index'])->name('dashboard.contact-messages.index');
        Route::get('/dashboard/contact-messages/{contact_message}', [ContactMessageController::class, 'show'])->name('dashboard.contact-messages.show');
        Route::delete('/dashboard/contact-messages/{contact_message}', [ContactMessageController::class, 'destroy'])->name('dashboard.contact-messages.destroy');
        Route::get('/dashboard/jobs', [JobAdminController::class, 'index'])->name('dashboard.jobs.index');
        Route::get('/dashboard/jobs/create', [JobAdminController::class, 'create'])->name('dashboard.jobs.create');
        Route::post('/dashboard/jobs', [JobAdminController::class, 'store'])->name('dashboard.jobs.store');
        Route::get('/dashboard/jobs/{job}/edit', [JobAdminController::class, 'edit'])->name('dashboard.jobs.edit');
        Route::put('/dashboard/jobs/{job}', [JobAdminController::class, 'update'])->name('dashboard.jobs.update');
        Route::delete('/dashboard/jobs/{job}', [JobAdminController::class, 'destroy'])->name('dashboard.jobs.destroy');
        Route::get('/dashboard/job-applications', [JobApplicationAdminController::class, 'index'])->name('dashboard.job-applications.index');
        Route::get('/dashboard/job-applications/{job_application}', [JobApplicationAdminController::class, 'show'])->name('dashboard.job-applications.show');
        Route::get('/dashboard/job-applications/{job_application}/cv', [JobApplicationAdminController::class, 'downloadCv'])->name('dashboard.job-applications.cv');
        Route::delete('/dashboard/job-applications/{job_application}', [JobApplicationAdminController::class, 'destroy'])->name('dashboard.job-applications.destroy');

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
