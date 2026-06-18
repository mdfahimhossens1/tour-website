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

    Route::get('/destinations', [DestinationApiController::class, 'index']);

    Route::get('/testimonials', [TestimonialApiController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | TOURS (API KEY PROTECTED - PUBLIC READ ONLY)
    |--------------------------------------------------------------------------
    */

    Route::prefix('tours')->middleware('apikey')->group(function () {

        Route::get('/', [TourApiController::class, 'index']);

        Route::get('/{id}', [TourApiController::class, 'show']);

        Route::get('/search', [TourApiController::class, 'search']);
    });


    /*
    |--------------------------------------------------------------------------
    | BOOKING SYSTEM (AUTH REQUIRED)
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/booking', [BookingApiController::class, 'store']);

        Route::get('/booking/{id}', [BookingApiController::class, 'show']);

        Route::get('/user/bookings', [UserBookingController::class, 'index']);
    });

});