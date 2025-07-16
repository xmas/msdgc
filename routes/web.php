<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PasswordlessLoginController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'appDebug' => config('app.debug'),
    ]);
})->name('home');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('/board', function () {
    return Inertia::render('Board');
})->name('board');

Route::get('/board/minutes/{id}', function (string $id) {
    return Inertia::render('BoardMinutes', [
        'minuteId' => $id,
    ]);
})->name('board.minutes.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        return Inertia::render('Dashboard', [
            'sessions' => [],
            'confirmsTwoFactorAuthentication' => false,
        ]);
    })->name('dashboard');

    // Members management route
    Route::get('/members', function () {
        return Inertia::render('Members');
    })->name('members');

    // Messages routes
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/send', [App\Http\Controllers\MessageController::class, 'send'])->name('messages.send');
});

// Passwordless login routes
Route::get('/passwordless-login', [PasswordlessLoginController::class, 'show'])
    ->middleware('guest')
    ->name('passwordless.show');

Route::post('/passwordless-login', [PasswordlessLoginController::class, 'sendLink'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('passwordless.send-link');

Route::get('/login/{token}', [PasswordlessLoginController::class, 'login'])
    ->middleware('guest')
    ->name('passwordless.login');

Route::get('/complete-signup/{token}', [PasswordlessLoginController::class, 'collectName'])
    ->middleware('guest')
    ->name('passwordless.collect-name');

Route::post('/complete-signup/{token}', [PasswordlessLoginController::class, 'completeName'])
    ->middleware('guest')
    ->name('passwordless.complete-name');

Route::post('/resend-passwordless-link', [PasswordlessLoginController::class, 'resendLink'])
    ->middleware(['guest', 'throttle:3,1'])
    ->name('passwordless.resend-link');
