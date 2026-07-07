<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Api\TourApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\DestinationApiController;
use App\Http\Controllers\Api\TestimonialApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\UserBookingController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\TourTypeApiController;

/*
|--------------------------------------------------------------------------
| API VERSIONING
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH ROUTES
    |--------------------------------------------------------------------------
    */

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
    });


    /*
    |--------------------------------------------------------------------------
    | PUBLIC DATA (HOME PAGE)
    |--------------------------------------------------------------------------
    */

    Route::get('/home', [HomeApiController::class, 'index']);
Route::get('/tour/{id}/dates', [HomeApiController::class, 'getTourDates']);
    Route::get('/destinations', [DestinationApiController::class, 'index']);

    Route::get('/testimonials', [TestimonialApiController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | TOURS (API KEY PROTECTED - PUBLIC READ ONLY)
    |--------------------------------------------------------------------------
    */

Route::prefix('tours')->middleware('')->group(function () {

    // All Tours
    Route::get('/', [TourApiController::class, 'index']);

    // Search Tours
    Route::get('/search', [TourApiController::class, 'search']);

    // Single Tour By Slug
    Route::get('/slug/{slug}', [TourApiController::class, 'show']);

    // Tour Dates By Slug
    Route::get('/{slug}/dates', [TourApiController::class, 'dates']);
    Route::get('/booking/package/{slug}', [TourApiController::class, 'booking']

    );

});

    Route::prefix('tour-types')->group(function () {

    Route::get('/', [TourTypeApiController::class, 'index']);

    Route::get('/{slug}', [TourTypeApiController::class, 'show']);

});


    /*
    |--------------------------------------------------------------------------
    | BOOKING SYSTEM (AUTH REQUIRED)
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/bookings', [BookingApiController::class, 'store']);

        Route::get('/bookings', [BookingApiController::class, 'index']);

        Route::get('/user/bookings', [UserBookingController::class, 'index']);
    });

    /*
|--------------------------------------------------------------------------
| PAYMENT SYSTEM
|--------------------------------------------------------------------------
*/

Route::post('/payments', [PaymentApiController::class, 'store']);

Route::patch('/payments/{id}/approve', [PaymentApiController::class, 'approve']);

Route::patch('/payments/{id}/reject', [PaymentApiController::class, 'reject']);

});