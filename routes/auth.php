<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| ADMIN & VENDOR LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/login',
    [AuthenticatedSessionController::class, 'adminCreate']
)->name('admin.login');

Route::post(
    '/admin/login',
    [AuthenticatedSessionController::class, 'adminStore']
)->name('admin.login.store');


Route::get(
    '/vendor/login',
    [AuthenticatedSessionController::class, 'vendorCreate']
)->name('vendor.login');

Route::post(
    '/vendor/login',
    [AuthenticatedSessionController::class, 'vendorStore']
)->name('vendor.login.store');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/forgot-password',
        [PasswordResetLinkController::class, 'create']
    )->name('password.request');

    Route::post(
        '/forgot-password',
        [PasswordResetLinkController::class, 'store']
    )->name('password.email');


    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reset-password/{token}',
        [NewPasswordController::class, 'create']
    )->name('password.reset');

    Route::post(
        '/reset-password',
        [NewPasswordController::class, 'store']
    )->name('password.store');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | EMAIL VERIFICATION
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/verify-email',
        EmailVerificationPromptController::class
    )->name('verification.notice');

    Route::get(
        '/verify-email/{id}/{hash}',
        VerifyEmailController::class
    )
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post(
        '/email/verification-notification',
        [EmailVerificationNotificationController::class, 'store']
    )
        ->middleware('throttle:6,1')
        ->name('verification.send');


    /*
    |--------------------------------------------------------------------------
    | CONFIRM PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/confirm-password',
        [ConfirmablePasswordController::class, 'show']
    )->name('password.confirm');

    Route::post(
        '/confirm-password',
        [ConfirmablePasswordController::class, 'store']
    );


    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/password',
        [PasswordController::class, 'update']
    )->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [AuthenticatedSessionController::class, 'destroy']
    )->name('logout');
});