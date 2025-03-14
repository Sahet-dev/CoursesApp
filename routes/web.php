<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


Route::get('/send-test-email', function () {
    Mail::raw('This is a test email from Laravel!', function ($message) {
        $message->to('kakajansahy@gmail.com')
        ->subject('Test Email from Laravel');
    });

    return 'Test email has been sent!';
});

// in web.php
Route::get('storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('filename', '.*');

Route::get('/', [HomeController::class, 'main'])->name('main-page');

Route::get('/courses', [HomeController::class, 'search'])->name('courses.search');
Route::get('/user/{id}', [ProfileController::class, 'profilePage'])->name('profilePage');
Route::get('/courses/{id}', [HomeController::class, 'show'])->name('courseDetail');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/courses/{courseId}/lessons/{lessonId}/comments/{commentId}/toggle-like', [HomeController::class, 'toggleLike'])
        ->name('comments.toggleLike')
        ->middleware('auth');

    Route::get('/notifications', [HomeController::class, 'notificationsPage'])->name('notifications');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-images', [ProfileController::class, 'uploadAvatar'])->name('profile.updateImages');

    Route::post('/feedback', [FeedbackController::class, 'store']);

    Route::get('/feedback', function () {
        return Inertia::render('components/FeedbackForm');
    })->name('feedback');

});
//route::get('/success', [SubscriptionController::class, 'success'])->name('payment.success')->middleware('web');
require __DIR__.'/auth.php';
