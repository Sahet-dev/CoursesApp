<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




Route::get('/', [HomeController::class, 'main'])->name('main-page');
Route::get('/courses', [HomeController::class, 'search'])->name('courses.search');
Route::get('/courses/{id}', [HomeController::class, 'show'])->name('courseDetail');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/courses/{courseId}/lessons/{lessonId}/comments/{commentId}/toggle-like', [HomeController::class, 'toggleLike'])
        ->name('comments.toggleLike')
        ->middleware('auth');

    Route::post('/courses/{course}/lessons/{lesson}/comments', [HomeController::class, 'createComment'])->name('createComment');
    Route::get('/notifications', [HomeController::class, 'notificationsPage'])->name('notifications');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
