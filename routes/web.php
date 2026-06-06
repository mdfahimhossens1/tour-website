<?php

use Illuminate\Support\Facades\Route;
// ==========================================
// FRONTEND CONTROLLERS
// ==========================================
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SubscriberController as FrontSubscriberController;
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
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\SeoSettingController;
use App\Http\Controllers\Admin\ApiKeyController;
use App\Http\Controllers\Admin\VendorController;
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

Route::post('/subscribe', [FrontSubscriberController::class,'store'])->name('subscriber.store');

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

    Route::middleware(['role:admin,super_admin'])->group(function () {

    Route::get('/vendors', [VendorController::class,'index'])
        ->name('admin.vendors.index');
    Route::post('/vendors/approve/{id}', [VendorController::class,'approve'])
        ->name('admin.vendors.approve');
    Route::post('/vendors/delete/{id}', [VendorController::class,'destroy'])
        ->name('admin.vendors.delete');

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

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

        Route::get('/ads', [AdsController::class,'index'])
            ->name('admin.ads.index');

        Route::get('/ads/create', [AdsController::class,'create'])
            ->name('admin.ads.create');

        Route::post('/ads/store', [AdsController::class,'store'])
            ->name('admin.ads.store');

        Route::get('/ads/edit/{id}', [AdsController::class,'edit'])
            ->name('admin.ads.edit');

        Route::post('/ads/update/{id}', [AdsController::class,'update'])
            ->name('admin.ads.update');

        Route::post('/ads/delete/{id}', [AdsController::class,'destroy'])
            ->name('admin.ads.delete');

    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    Route::get('/transactions', [TransactionController::class,'index'])
        ->name('admin.transactions.index');

    Route::get('/transactions/{id}', [TransactionController::class,'show'])
        ->name('admin.transactions.show');

    Route::post('/transactions/{id}/status', [TransactionController::class,'updateStatus'])
        ->name('admin.transactions.status');

    Route::post('/transactions/{id}/delete', [TransactionController::class,'destroy'])
        ->name('admin.transactions.delete');
    Route::get('/transactions/{id}/invoice', [TransactionController::class, 'invoice'])
        ->name('admin.transactions.invoice');
    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])
            ->name('admin.payment_methods.index');

        Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])
            ->name('admin.payment_methods.create');

        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])
            ->name('admin.payment_methods.store');

        Route::get('/payment-methods/{id}/edit', [PaymentMethodController::class, 'edit'])
            ->name('admin.payment_methods.edit');

        Route::post('/payment-methods/{id}/update', [PaymentMethodController::class, 'update'])
            ->name('admin.payment_methods.update');

        Route::get('/payment-methods/{id}/delete', [PaymentMethodController::class, 'destroy'])
            ->name('admin.payment_methods.delete');

    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {
        Route::get('/contact-messages',[ContactMessageController::class,'index'])->name('admin.contact.index');
        Route::get('/contact-messages/{id}',[ContactMessageController::class,'show'])->name('admin.contact.show');
        Route::post('/contact-messages/delete/{id}',[ContactMessageController::class,'destroy'])->name('admin.contact.delete');
    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {
        Route::get('/subscribers', [SubscriberController::class,'index'])->name('admin.subscribers.index');
        Route::post('/subscribers/delete/{id}', [SubscriberController::class,'destroy'])->name('admin.subscribers.delete');
    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

        Route::get('/blogs', [BlogController::class,'index'])
            ->name('admin.blogs.index');

        Route::get('/blogs/create', [BlogController::class,'create'])
            ->name('admin.blogs.create');

        Route::post('/blogs/store', [BlogController::class,'store'])
            ->name('admin.blogs.store');

        Route::get('/blogs/show/{slug}', [BlogController::class,'show'])
            ->name('admin.blogs.show');

        Route::get('/blogs/edit/{slug}', [BlogController::class,'edit'])
            ->name('admin.blogs.edit');

        Route::post('/blogs/update/{slug}', [BlogController::class,'update'])
            ->name('admin.blogs.update');

        Route::post('/blogs/delete/{slug}', [BlogController::class,'destroy'])
            ->name('admin.blogs.delete');

        Route::get('/blog-categories', [BlogCategoryController::class,'index'])
        ->name('admin.blog.categories.index');

        Route::post('/blog-categories/store', [BlogCategoryController::class,'store'])
            ->name('admin.blog.categories.store');

        Route::get('/blog-categories/edit/{id}', [BlogCategoryController::class,'edit'])
            ->name('admin.blog.categories.edit');

        Route::post('/blog-categories/update/{id}', [BlogCategoryController::class,'update'])
            ->name('admin.blog.categories.update');

        Route::post('/blog-categories/delete/{id}', [BlogCategoryController::class,'destroy'])
            ->name('admin.blog.categories.delete');

    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    Route::get('/gallery', [GalleryController::class,'index'])
        ->name('admin.gallery.index');

    Route::get('/gallery/create', [GalleryController::class,'create'])
        ->name('admin.gallery.create');

    Route::post('/gallery/store', [GalleryController::class,'store'])
        ->name('admin.gallery.store');

    Route::post('/gallery/delete/{id}', [GalleryController::class,'destroy'])
        ->name('admin.gallery.delete');

    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    Route::get('/testimonials', [TestimonialController::class,'index'])
        ->name('admin.testimonials.index');
    Route::get('/testimonials/create', [TestimonialController::class,'create'])
        ->name('admin.testimonials.create');
    Route::post('/testimonials/store', [TestimonialController::class,'store'])
        ->name('admin.testimonials.store');
    Route::post('/testimonials/delete/{id}', [TestimonialController::class,'destroy'])
        ->name('admin.testimonials.delete');

    });

    Route::middleware(['role:manager,admin,super_admin'])->group(function () {

    Route::get('/seo-settings', [SeoSettingController::class,'index'])
        ->name('admin.seo.index');

    Route::get('/seo-settings/create', [SeoSettingController::class,'create'])
        ->name('admin.seo.create');

    Route::post('/seo-settings/store', [SeoSettingController::class,'store'])
        ->name('admin.seo.store');

    Route::get('/seo-settings/edit/{id}', [SeoSettingController::class,'edit'])
        ->name('admin.seo.edit');

    Route::post('/seo-settings/update/{id}', [SeoSettingController::class,'update'])
        ->name('admin.seo.update');

    Route::post('/seo-settings/delete/{id}', [SeoSettingController::class,'destroy'])
        ->name('admin.seo.delete');

    });

    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/api-keys',[ApiKeyController::class,'index'])->name('admin.api.keys.index');
        Route::get('/api-keys/create', [ApiKeyController::class,'create']
        )->name('admin.api.keys.create');
        Route::post( '/api-keys/store', [ApiKeyController::class,'store']
        )->name('admin.api.keys.store');

        Route::post('/api-keys/status/{id}', [ApiKeyController::class,'status']
        )->name('admin.api.keys.status');

        Route::post('/api-keys/delete/{id}',[ApiKeyController::class,'destroy']
        )->name('admin.api.keys.delete');
    });

});


// ==========================================
// AUTH ROUTES
// ==========================================

require __DIR__.'/auth.php';