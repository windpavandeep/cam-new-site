<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
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
    });
});
