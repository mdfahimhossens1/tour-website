<?php

use Illuminate\Support\Facades\Route;
// ==========================================
// FRONTEND CONTROLLERS
// ==========================================
use App\Http\Controllers\Frontend\HomeController;
// ==========================================
// ADMIN CONTROLLERS
// ==========================================
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\TourDateController;
use App\Http\Controllers\Admin\TravelerController;
use App\Http\Controllers\Admin\BookingReportController;
// ==========================================
// FRONTEND PUBLIC ROUTES
// ==========================================

Route::get('/', function () {

    return view('welcome');

})->name('home');

// ==========================================
// FRONTEND AUTH USER ROUTES
// ==========================================

Route::middleware(['auth'])->group(function () {

    // USER DASHBOARD
    Route::get('/user/dashboard', function () {

        return view('frontend.user.dashboard');

    })->name('user.dashboard');

});


// ==========================================
// ADMIN PANEL ROUTES
// ==========================================

Route::prefix('admin')
    ->middleware(['auth', 'role:manager,admin,super_admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [ProfileController::class, 'adminProfile'])
            ->name('admin.profile');

        Route::post('/profile', [ProfileController::class, 'adminProfileUpdate'])
            ->name('admin.profile.update');

        Route::get('/manage-account', [ProfileController::class, 'adminAccount'])
            ->name('admin.account');

        Route::post('/manage-account/password', [ProfileController::class, 'adminPasswordUpdate'])
            ->name('admin.account.password');


        /*
        |--------------------------------------------------------------------------
        | MANAGER+
        |--------------------------------------------------------------------------
        */

        Route::middleware(['role:manager'])->group(function () {

            // Notifications
            Route::get('/notifications/poll', [NotificationController::class, 'poll'])
                ->name('admin.notifications.poll');

            Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
                ->name('admin.notifications.markAllRead');

            Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])
                ->name('admin.notifications.clearAll');


            // Coupons
            Route::get('/coupons', [CouponController::class, 'index'])
                ->name('admin.coupons.index');

            Route::get('/coupons/add', [CouponController::class, 'create'])
                ->name('admin.coupons.create');

            Route::post('/coupons/store', [CouponController::class, 'store'])
                ->name('admin.coupons.store');

            Route::get('/coupons/edit/{slug}', [CouponController::class, 'edit'])
                ->name('admin.coupons.edit');

            Route::post('/coupons/update/{slug}', [CouponController::class, 'update'])
                ->name('admin.coupons.update');

            Route::post('/coupons/delete/{id}', [CouponController::class, 'destroy'])
                ->name('admin.coupons.delete');

        });


        /*
        |--------------------------------------------------------------------------
        | ADMIN+
        |--------------------------------------------------------------------------
        */

Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('admin.settings.index');

    Route::get('/settings/general', [SettingsController::class, 'general'])
        ->name('admin.settings.general');

    Route::post('/settings/general', [SettingsController::class, 'generalUpdate'])
        ->name('admin.settings.general.update');

    Route::get('/settings/payment', [SettingsController::class, 'payment'])
        ->name('admin.settings.payment');

    Route::post('/settings/payment', [SettingsController::class, 'paymentUpdate'])
        ->name('admin.settings.payment.update');
});

        /*
        |--------------------------------------------------------------------------
        | ADMIN / SUPER ADMIN / MANAGER
        |--------------------------------------------------------------------------
        */

        Route::middleware(['role:manager,admin,super_admin'])->group(function () {

            // Users
            Route::get('/users', [UserController::class, 'index'])
                ->name('admin.users.index');

            Route::get('/users/staff', [UserController::class, 'staff'])
                ->name('admin.users.staff');

            Route::get('/users/add', [UserController::class, 'add'])
                ->name('admin.users.add');

            Route::post('/users/store', [UserController::class, 'store'])
                ->name('admin.users.store');

            Route::get('/users/view/{slug}', [UserController::class, 'show'])
                ->name('admin.users.show');

            Route::get('/users/edit/{slug}', [UserController::class, 'edit'])
                ->name('admin.users.edit');

            Route::post('/users/update/{slug}', [UserController::class, 'update'])
                ->name('admin.users.update');

        });


    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    Route::get('/tour-packages', [TourPackageController::class, 'index'])
        ->name('admin.tours.index');

    Route::get('/tour-packages/add', [TourPackageController::class, 'create'])
        ->name('admin.tours.create');

    Route::post('/tour-packages/store', [TourPackageController::class, 'store'])
        ->name('admin.tours.store');

    Route::get('/tours/view/{slug}', [TourPackageController::class, 'show'])
    ->name('admin.tours.show');

    Route::get('/tours/edit/{slug}', [TourPackageController::class, 'edit'])
        ->name('admin.tours.edit');

    Route::post('/tours/update/{slug}', [TourPackageController::class, 'update'])
        ->name('admin.tours.update');

    Route::post('/tours/delete/{id}', [TourPackageController::class, 'destroy'])
        ->name('admin.tours.delete');
    Route::get('/tours/{id}/modal-data', [TourPackageController::class, 'modalData'])
    ->name('admin.tours.modal-data');


    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {
    Route::get('/bookings/pending', [BookingController::class, 'pending'])
        ->name('admin.bookings.pending');

    Route::get('/bookings/confirmed', [BookingController::class, 'confirmed'])
        ->name('admin.bookings.confirmed');

    Route::get('/bookings/view/{id}', [BookingController::class, 'show'])
        ->name('admin.bookings.show');

    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm'])
        ->name('admin.bookings.confirm');

    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
        ->name('admin.bookings.cancel');

    });


    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    Route::get('/tour-dates', [TourDateController::class, 'index'])
        ->name('admin.tour.dates.index');

    Route::get('/tour-dates/add', [TourDateController::class, 'create'])
        ->name('admin.tour.dates.create');

    Route::post('/tour-dates/store', [TourDateController::class, 'store'])
        ->name('admin.tour.dates.store');

    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {
    Route::get('/destinations', [DestinationController::class, 'index'])
    ->name('admin.destinations.index');
    Route::get('/destinations/create', [DestinationController::class, 'create'])
        ->name('admin.destinations.create');
    Route::post('/destinations/store', [DestinationController::class, 'store'])
        ->name('admin.destinations.store');
    Route::get('/destinations/edit/{slug}', [DestinationController::class, 'edit'])
        ->name('admin.destinations.edit');
    Route::post('/destinations/update/{slug}', [DestinationController::class, 'update'])
        ->name('admin.destinations.update');
    Route::post('/destinations/delete/{id}', [DestinationController::class, 'destroy'])
        ->name('admin.destinations.delete');
    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {
    Route::get('/travelers', [TravelerController::class, 'index'])
        ->name('admin.travelers.index');
    Route::get('/travelers/add', [TravelerController::class, 'create'])
        ->name('admin.travelers.create');
    Route::post('/travelers/store', [TravelerController::class, 'store'])
        ->name('admin.travelers.store');

    });

Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    Route::get('/reports/bookings', [BookingReportController::class, 'bookingReport'])
        ->name('admin.reports.bookings');

    Route::get('/reports/revenue', [BookingReportController::class, 'revenueReport'])
        ->name('admin.reports.revenue');

});

});


// ==========================================
// AUTH ROUTES
// ==========================================

require __DIR__.'/auth.php';