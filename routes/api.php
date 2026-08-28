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
use App\Http\Controllers\Api\FAQApiController;
use App\Http\Controllers\Api\ContactMessageApiController;
use App\Http\Controllers\Api\PolicyApiController;
use App\Http\Controllers\Api\TeamMemberApiController;
use App\Http\Controllers\Api\ResortApiController;
use App\Http\Controllers\Api\RoomApiController;
use App\Http\Controllers\Api\RoomBookingApiController;
use App\Http\Controllers\Api\PaymentMethodsApiController;
use App\Http\Controllers\Api\VendorPaymentMethodsApiController;
use App\Http\Controllers\Api\User\WalletController;
use App\Http\Controllers\Api\User\UserTransactionController;
use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\SubscriberApiController;
use App\Http\Controllers\Api\TransportApiController;
use App\Http\Controllers\Api\TransportBookingApiController;


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
        Route::post('/user/profile', [ProfileController::class, 'update']);
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


Route::middleware('auth:sanctum')->prefix('user')->group(function () {

    Route::get('/wallet', [WalletController::class, 'index']);

});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {

    Route::get('/transactions', [
        UserTransactionController::class,
        'index'
    ]);

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

Route::get('/faqs', [FAQApiController::class, 'index']);

Route::post('/contact-messages', [ContactMessageApiController::class, 'store']);

Route::get('/policies', [PolicyApiController::class, 'index']);

Route::get('/team-members', [TeamMemberApiController::class, 'index']);

Route::get('/featured-resorts', [ResortApiController::class, 'featured']);
Route::get('/resorts', [ResortApiController::class, 'index']);
Route::get('/resorts/search', [ResortApiController::class, 'search']);
Route::get('/resorts/{slug}', [ResortApiController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Rooms
|--------------------------------------------------------------------------
*/

Route::prefix('rooms')->group(function () {

    Route::get(
        '/featured',
        [RoomApiController::class, 'featured']
    );

    Route::get(
        '/search',
        [RoomApiController::class, 'search']
    );

    Route::get(
        '/resort/{resortId}',
        [RoomApiController::class, 'byResort']
    );

    Route::get(
        '/',
        [RoomApiController::class, 'index']
    );

    Route::get(
        '/slug/{slug}',
        [RoomApiController::class, 'showBySlug']
    );

    Route::get(
        '/{id}',
        [RoomApiController::class, 'show']
    );

});

Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        '/room-bookings',
        [RoomBookingApiController::class, 'store']
    );

    Route::get(
        '/room-bookings/{booking}',
        [RoomBookingApiController::class, 'show']
    );

});
Route::get(
    '/payment-methods',
    [PaymentMethodsApiController::class, 'index']
);
Route::get(
    '/vendor/payment-methods',
    [VendorPaymentMethodsApiController::class, 'index']
);

Route::get(
    '/vendor-payment-methods',
    [VendorPaymentMethodsApiController::class, 'index']
);

Route::get('/ads', [
    AdsController::class,
    'index'
]);

Route::post('/ads/{id}/view', [
    AdsController::class,
    'view'
]);

Route::post('/ads/{id}/click', [
    AdsController::class,
    'click'
]);

Route::post('/subscribers', [
    SubscriberApiController::class,
    'store'
]);

Route::get('/transport', [TransportApiController::class, 'index']);
Route::get('/transport/featured', [TransportApiController::class, 'featured']);
Route::get('/transport/types', [TransportApiController::class, 'types']);
Route::get('/transport/{slug}', [TransportApiController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Transport Bookings
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

        Route::get(
            '/transport-bookings',
            [TransportBookingApiController::class, 'index']
        );

        Route::post(
            '/transport-bookings',
            [TransportBookingApiController::class, 'store']
        );

        Route::get(
            '/transport-bookings/{booking}',
            [TransportBookingApiController::class, 'show']
        );
});

});

