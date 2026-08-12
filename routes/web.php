<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\CandidateController;

use Illuminate\Support\Facades\Route;

// Public Routes (Zero Auth Wall)
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/events', [PublicController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [PublicController::class, 'showEvent'])->name('public.events.show');
Route::get('/events/{eventSlug}/candidates/{candidateSlug}', [PublicController::class, 'showCandidate'])->name('public.candidates.show');
Route::get('/register-event', [PublicController::class, 'registerEvent'])->name('public.register-event');

// Admin Routes (Tanpa Link Publik)
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    });

    // Authenticated Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Sprint 2 Routes
        Route::resource('events', EventController::class);
        Route::resource('categories', EventCategoryController::class)->except(['show']);
        Route::resource('candidates', CandidateController::class);
    });
});

require __DIR__.'/auth.php';
