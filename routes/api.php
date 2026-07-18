<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\DestinationApiController;
use App\Http\Controllers\Api\TestimonialApiController;
use App\Http\Controllers\Api\TourApiController;
use App\Http\Controllers\Api\TourTypeApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\UserBookingController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\User\NotificationController;
use App\Http\Controllers\Api\User\PaymentHistoryController;
use App\Http\Controllers\Api\User\ReviewController;
use App\Http\Controllers\Api\AITripPlannerController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\BlogCategoryApiController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'index']);
        Route::get('/user/profile', [ProfileController::class, 'index']);
        Route::put('/user/profile', [ProfileController::class, 'update']);
        Route::get('/user/wishlist', [WishlistController::class, 'index']);
        Route::post('/user/wishlist', [WishlistController::class, 'store']);
        Route::delete('/user/wishlist/{tourId}', [WishlistController::class, 'destroy']);
        Route::get('/user/reviews', [ReviewController::class, 'index']);
        Route::post('/user/reviews', [ReviewController::class, 'store']);
        Route::put('/user/reviews/{review}', [ReviewController::class, 'update']);
        Route::delete('/user/reviews/{review}', [ReviewController::class, 'destroy']);
       Route::get('/user/notifications', [NotificationController::class, 'index']);
        Route::get('/user/notifications/count', [NotificationController::class, 'count']);
        Route::patch('/user/notifications/{id}/read', [NotificationController::class, 'markRead']);
        Route::patch('/user/notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::delete('/user/notifications/{id}', [NotificationController::class, 'destroy']);
        Route::get('/user/payments', [PaymentHistoryController::class, 'index']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

    });

    /*
    |--------------------------------------------------------------------------
    | Home
    |--------------------------------------------------------------------------
    */

    Route::get('/home', [HomeApiController::class, 'index']);
    Route::get('/tour/{id}/dates', [HomeApiController::class, 'getTourDates']);

    /*
    |--------------------------------------------------------------------------
    | Destinations
    |--------------------------------------------------------------------------
    */

    Route::get('/destinations', [DestinationApiController::class, 'index']);
    Route::get('/destinations/trending', [DestinationApiController::class, 'trending']);

    /*
    |--------------------------------------------------------------------------
    | Testimonials
    |--------------------------------------------------------------------------
    */

    Route::get('/testimonials', [TestimonialApiController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Tours
    |--------------------------------------------------------------------------
    */

    Route::prefix('tours')->group(function () {

        Route::get('/', [TourApiController::class, 'index']);

        Route::get('/slug/{slug}', [TourApiController::class, 'show']);

        Route::get('/{slug}/dates', [TourApiController::class, 'dates']);

        Route::get('/booking/package/{slug}', [TourApiController::class, 'booking']);

    });

    /*
    |--------------------------------------------------------------------------
    | Tour Types
    |--------------------------------------------------------------------------
    */

    Route::prefix('tour-types')->group(function () {

        Route::get('/', [TourTypeApiController::class, 'index']);

        Route::get('/{slug}', [TourTypeApiController::class, 'show']);

    });

    /*
    |--------------------------------------------------------------------------
    | Booking (Login Required)
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/bookings', [BookingApiController::class, 'store']);

        Route::get('/bookings', [BookingApiController::class, 'index']);

        Route::get('/user/bookings', [UserBookingController::class, 'index']);

    });

    /*
    |--------------------------------------------------------------------------
    | Payment
    |--------------------------------------------------------------------------
    */

    Route::post('/payments', [PaymentApiController::class, 'store']);

    Route::patch('/payments/{id}/approve', [PaymentApiController::class, 'approve']);

    Route::patch('/payments/{id}/reject', [PaymentApiController::class, 'reject']);

    Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        '/ai-trip-planner',
        [AITripPlannerController::class, 'generate']
    );

  

});
  /*
|--------------------------------------------------------------------------
| Blog
|--------------------------------------------------------------------------
*/

Route::prefix('blogs')->group(function () {

    Route::get('/', [BlogApiController::class, 'index']);

    Route::get('/{slug}', [BlogApiController::class, 'show']);

});
/*
|--------------------------------------------------------------------------
| Blog Categories
|--------------------------------------------------------------------------
*/

Route::get(
    '/blog-categories',
    [BlogCategoryApiController::class, 'index']
);

});

