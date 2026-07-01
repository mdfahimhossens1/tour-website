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

Route::prefix('tours')->middleware('apikey')->group(function () {

    // All Tours
    Route::get('/', [TourApiController::class, 'index']);

    // Search Tours
    Route::get('/search', [TourApiController::class, 'search']);

    // Single Tour By Slug
    Route::get('/slug/{slug}', [TourApiController::class, 'showBySlug']);

    // Tour Dates By Slug
    Route::get('/{slug}/dates', [TourApiController::class, 'dates']);

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

});