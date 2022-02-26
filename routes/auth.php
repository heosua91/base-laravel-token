<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:sanctum'], function () {
    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});
