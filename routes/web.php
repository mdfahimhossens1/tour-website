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
// VENDOR CONTROLLERS
// ==========================================
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorTourController;
use App\Http\Controllers\Vendor\VendorBookingController;
use App\Http\Controllers\Vendor\VendorEarningController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorWalletController;
use App\Http\Controllers\Vendor\VendorWithdrawalController;

// ==========================================
// FRONTEND PUBLIC ROUTES
// ==========================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/subscribe', [FrontSubscriberController::class, 'store'])->name('subscriber.store');


// ==========================================
// USER (CUSTOMER) ROUTES — /user
// ==========================================

Route::prefix('user')
    ->middleware(['auth', 'role:user'])
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('frontend.user.dashboard');
        })->name('dashboard');

        // TODO: Add user booking, payment, history, review routes here

    });


// ==========================================
// VENDOR PANEL ROUTES — /vendor
// ==========================================

Route::prefix('vendor')
    ->middleware(['auth', 'role:vendor'])
    ->name('vendor.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | PROFILE — own profile only
        |--------------------------------------------------------------------------
        */
        Route::get('/profile', [VendorProfileController::class, 'index'])
            ->name('profile');
        Route::post('/profile', [VendorProfileController::class, 'update'])
            ->name('profile.update');

        /*
        |--------------------------------------------------------------------------
        | TOURS — own tours/packages only
        |--------------------------------------------------------------------------
        */
        Route::get('/tours', [VendorTourController::class, 'index'])
            ->name('tours.index');
        Route::get('/tours/create', [VendorTourController::class, 'create'])
            ->name('tours.create');
        Route::post('/tours/store', [VendorTourController::class, 'store'])
            ->name('tours.store');
        Route::get('/tours/edit/{slug}', [VendorTourController::class, 'edit'])
            ->name('tours.edit');
        Route::post('/tours/update/{slug}', [VendorTourController::class, 'update'])
            ->name('tours.update');
        Route::post('/tours/delete/{id}', [VendorTourController::class, 'destroy'])
            ->name('tours.delete');

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS — own bookings only
        |--------------------------------------------------------------------------
        */
        Route::get('/bookings', [VendorBookingController::class, 'index'])
            ->name('bookings.index');
        Route::get('/bookings/view/{id}', [VendorBookingController::class, 'show'])
            ->name('bookings.show');
        Route::post('/bookings/confirm/{id}', [VendorBookingController::class, 'confirm'])
    ->name('bookings.confirm');

    Route::post('/bookings/cancel/{id}', [VendorBookingController::class, 'cancel'])
        ->name('bookings.cancel');

        /*
        |--------------------------------------------------------------------------
        | EARNINGS / COMMISSION
        |--------------------------------------------------------------------------
        */
        Route::get('/earnings', [VendorEarningController::class, 'index'])
            ->name('earnings.index');

        Route::get('/wallet', [VendorWalletController::class, 'index'])
        ->name('wallet.index');

        Route::get('/withdrawals', [VendorWithdrawalController::class, 'index'])
        ->name('withdrawals.index');

        Route::post('/withdrawals/store', [VendorWithdrawalController::class, 'store'])
        ->name('withdrawals.store');

    });


// ==========================================
// ADMIN PANEL ROUTES — /admin
// ==========================================

Route::prefix('admin')
    ->middleware(['auth', 'role:manager,admin,super_admin'])
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | PROFILE — manager, admin, super_admin (own profile only)
        |--------------------------------------------------------------------------
        */
        Route::get('/profile', [ProfileController::class, 'adminProfile'])
            ->name('profile');
        Route::post('/profile', [ProfileController::class, 'adminProfileUpdate'])
            ->name('profile.update');
        Route::get('/manage-account', [ProfileController::class, 'adminAccount'])
            ->name('account');
        Route::post('/manage-account/password', [ProfileController::class, 'adminPasswordUpdate'])
            ->name('account.password');

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/notifications/poll', [NotificationController::class, 'poll'])
            ->name('notifications.poll');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
            ->name('notifications.markAllRead');
        Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])
            ->name('notifications.clearAll');

        /*
        |--------------------------------------------------------------------------
        | TOUR PACKAGES — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/tour-packages', [TourPackageController::class, 'index'])
            ->name('tours.index');
        Route::get('/tour-packages/add', [TourPackageController::class, 'create'])
            ->name('tours.create');
        Route::post('/tour-packages/store', [TourPackageController::class, 'store'])
            ->name('tours.store');
        Route::get('/tours/view/{slug}', [TourPackageController::class, 'show'])
            ->name('tours.show');
        Route::get('/tours/edit/{slug}', [TourPackageController::class, 'edit'])
            ->name('tours.edit');
        Route::post('/tours/update/{slug}', [TourPackageController::class, 'update'])
            ->name('tours.update');
        Route::post('/tours/delete/{id}', [TourPackageController::class, 'destroy'])
            ->name('tours.delete');
        Route::get('/tours/{id}/modal-data', [TourPackageController::class, 'modalData'])
            ->name('tours.modal-data');

        /*
        |--------------------------------------------------------------------------
        | TOUR DATES — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/tour-dates', [TourDateController::class, 'index'])
            ->name('tour.dates.index');
        Route::get('/tour-dates/add', [TourDateController::class, 'create'])
            ->name('tour.dates.create');
        Route::post('/tour-dates/store', [TourDateController::class, 'store'])
            ->name('tour.dates.store');

        /*
        |--------------------------------------------------------------------------
        | DESTINATIONS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/destinations', [DestinationController::class, 'index'])
            ->name('destinations.index');
        Route::get('/destinations/create', [DestinationController::class, 'create'])
            ->name('destinations.create');
        Route::post('/destinations/store', [DestinationController::class, 'store'])
            ->name('destinations.store');
        Route::get('/destinations/edit/{slug}', [DestinationController::class, 'edit'])
            ->name('destinations.edit');
        Route::post('/destinations/update/{slug}', [DestinationController::class, 'update'])
            ->name('destinations.update');
        Route::post('/destinations/delete/{id}', [DestinationController::class, 'destroy'])
            ->name('destinations.delete');

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/bookings/pending', [BookingController::class, 'pending'])
            ->name('bookings.pending');
        Route::get('/bookings/confirmed', [BookingController::class, 'confirmed'])
            ->name('bookings.confirmed');
        Route::get('/bookings/view/{id}', [BookingController::class, 'show'])
            ->name('bookings.show');
        Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm'])
            ->name('bookings.confirm');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
            ->name('bookings.cancel');

        /*
        |--------------------------------------------------------------------------
        | TRAVELERS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/travelers', [TravelerController::class, 'index'])
            ->name('travelers.index');
        Route::get('/travelers/add', [TravelerController::class, 'create'])
            ->name('travelers.create');
        Route::post('/travelers/store', [TravelerController::class, 'store'])
            ->name('travelers.store');

        /*
        |--------------------------------------------------------------------------
        | REPORTS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/reports/bookings', [BookingReportController::class, 'bookingReport'])
            ->name('reports.bookings');
        Route::get('/reports/revenue', [BookingReportController::class, 'revenueReport'])
            ->name('reports.revenue');

        /*
        |--------------------------------------------------------------------------
        | TRANSACTIONS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])
            ->name('transactions.show');
        Route::post('/transactions/{id}/status', [TransactionController::class, 'updateStatus'])
            ->name('transactions.status');
        Route::post('/transactions/{id}/delete', [TransactionController::class, 'destroy'])
            ->name('transactions.delete');
        Route::get('/transactions/{id}/invoice', [TransactionController::class, 'invoice'])
            ->name('transactions.invoice');

        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHODS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])
            ->name('payment_methods.index');
        Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])
            ->name('payment_methods.create');
        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])
            ->name('payment_methods.store');
        Route::get('/payment-methods/{id}/edit', [PaymentMethodController::class, 'edit'])
            ->name('payment_methods.edit');
        Route::post('/payment-methods/{id}/update', [PaymentMethodController::class, 'update'])
            ->name('payment_methods.update');
        Route::get('/payment-methods/{id}/delete', [PaymentMethodController::class, 'destroy'])
            ->name('payment_methods.delete');

        /*
        |--------------------------------------------------------------------------
        | COUPONS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/coupons', [CouponController::class, 'index'])
            ->name('coupons.index');
        Route::get('/coupons/add', [CouponController::class, 'create'])
            ->name('coupons.create');
        Route::post('/coupons/store', [CouponController::class, 'store'])
            ->name('coupons.store');
        Route::get('/coupons/edit/{slug}', [CouponController::class, 'edit'])
            ->name('coupons.edit');
        Route::post('/coupons/update/{slug}', [CouponController::class, 'update'])
            ->name('coupons.update');
        Route::post('/coupons/delete/{id}', [CouponController::class, 'destroy'])
            ->name('coupons.delete');

        /*
        |--------------------------------------------------------------------------
        | ADS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/ads', [AdsController::class, 'index'])
            ->name('ads.index');
        Route::get('/ads/create', [AdsController::class, 'create'])
            ->name('ads.create');
        Route::post('/ads/store', [AdsController::class, 'store'])
            ->name('ads.store');
        Route::get('/ads/edit/{id}', [AdsController::class, 'edit'])
            ->name('ads.edit');
        Route::post('/ads/update/{id}', [AdsController::class, 'update'])
            ->name('ads.update');
        Route::post('/ads/delete/{id}', [AdsController::class, 'destroy'])
            ->name('ads.delete');

        /*
        |--------------------------------------------------------------------------
        | CONTACT MESSAGES — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])
            ->name('contact.index');
        Route::get('/contact-messages/{id}', [ContactMessageController::class, 'show'])
            ->name('contact.show');
        Route::post('/contact-messages/read/{id}', [ContactMessageController::class, 'markRead'])
            ->name('contact.read');
        Route::post('/contact-messages/delete/{id}', [ContactMessageController::class, 'destroy'])
            ->name('contact.delete');

        /*
        |--------------------------------------------------------------------------
        | SUBSCRIBERS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/subscribers', [SubscriberController::class, 'index'])
            ->name('subscribers.index');
        Route::post('/subscribers/delete/{id}', [SubscriberController::class, 'destroy'])
            ->name('subscribers.delete');

        /*
        |--------------------------------------------------------------------------
        | BLOGS & CATEGORIES — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/blogs', [BlogController::class, 'index'])
            ->name('blogs.index');
        Route::get('/blogs/create', [BlogController::class, 'create'])
            ->name('blogs.create');
        Route::post('/blogs/store', [BlogController::class, 'store'])
            ->name('blogs.store');
        Route::get('/blogs/show/{slug}', [BlogController::class, 'show'])
            ->name('blogs.show');
        Route::get('/blogs/edit/{slug}', [BlogController::class, 'edit'])
            ->name('blogs.edit');
        Route::post('/blogs/update/{slug}', [BlogController::class, 'update'])
            ->name('blogs.update');
        Route::post('/blogs/delete/{slug}', [BlogController::class, 'destroy'])
            ->name('blogs.delete');

        Route::get('/blog-categories', [BlogCategoryController::class, 'index'])
            ->name('blog.categories.index');
        Route::post('/blog-categories/store', [BlogCategoryController::class, 'store'])
            ->name('blog.categories.store');
        Route::get('/blog-categories/edit/{id}', [BlogCategoryController::class, 'edit'])
            ->name('blog.categories.edit');
        Route::post('/blog-categories/update/{id}', [BlogCategoryController::class, 'update'])
            ->name('blog.categories.update');
        Route::post('/blog-categories/delete/{id}', [BlogCategoryController::class, 'destroy'])
            ->name('blog.categories.delete');

        /*
        |--------------------------------------------------------------------------
        | GALLERY — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/gallery', [GalleryController::class, 'index'])
            ->name('gallery.index');
        Route::get('/gallery/create', [GalleryController::class, 'create'])
            ->name('gallery.create');
        Route::post('/gallery/store', [GalleryController::class, 'store'])
            ->name('gallery.store');
        Route::post('/gallery/delete/{id}', [GalleryController::class, 'destroy'])
            ->name('gallery.delete');

        /*
        |--------------------------------------------------------------------------
        | TESTIMONIALS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/testimonials', [TestimonialController::class, 'index'])
            ->name('testimonials.index');
        Route::get('/testimonials/create', [TestimonialController::class, 'create'])
            ->name('testimonials.create');
        Route::post('/testimonials/store', [TestimonialController::class, 'store'])
            ->name('testimonials.store');
        Route::post('/testimonials/delete/{id}', [TestimonialController::class, 'destroy'])
            ->name('testimonials.delete');

        /*
        |--------------------------------------------------------------------------
        | SEO SETTINGS — manager, admin, super_admin
        |--------------------------------------------------------------------------
        */
        Route::get('/seo-settings', [SeoSettingController::class, 'index'])
            ->name('seo.index');
        Route::get('/seo-settings/create', [SeoSettingController::class, 'create'])
            ->name('seo.create');
        Route::post('/seo-settings/store', [SeoSettingController::class, 'store'])
            ->name('seo.store');
        Route::get('/seo-settings/edit/{id}', [SeoSettingController::class, 'edit'])
            ->name('seo.edit');
        Route::post('/seo-settings/update/{id}', [SeoSettingController::class, 'update'])
            ->name('seo.update');
        Route::post('/seo-settings/delete/{id}', [SeoSettingController::class, 'destroy'])
            ->name('seo.delete');

        /*
        |--------------------------------------------------------------------------
        | USERS MANAGEMENT — admin, super_admin only
        | (manager cannot manage users)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:admin,super_admin'])->group(function () {

            Route::get('/users', [UserController::class, 'index'])
                ->name('users.index');
            Route::get('/users/staff', [UserController::class, 'staff'])
                ->name('users.staff');
            Route::get('/users/add', [UserController::class, 'add'])
                ->name('users.add');
            Route::post('/users/store', [UserController::class, 'store'])
                ->name('users.store');
            Route::get('/users/view/{slug}', [UserController::class, 'show'])
                ->name('users.show');
            Route::get('/users/edit/{slug}', [UserController::class, 'edit'])
                ->name('users.edit');
            Route::post('/users/update/{slug}', [UserController::class, 'update'])
                ->name('users.update');

        });

        /*
        |--------------------------------------------------------------------------
        | VENDOR MANAGEMENT — admin, super_admin only
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:admin,super_admin'])->group(function () {

            Route::get('/vendors', [VendorController::class, 'index'])
                ->name('vendors.index');
            Route::post('/vendors/approve/{id}', [VendorController::class, 'approve'])
                ->name('vendors.approve');
            Route::post('/vendors/delete/{id}', [VendorController::class, 'destroy'])
                ->name('vendors.delete');

        });

        /*
        |--------------------------------------------------------------------------
        | SYSTEM SETTINGS (General + Payment) — admin, super_admin only
        | (manager cannot change system settings)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:admin,super_admin'])->group(function () {

            Route::get('/settings', [SettingsController::class, 'index'])
                ->name('settings.index');
            Route::get('/settings/general', [SettingsController::class, 'general'])
                ->name('settings.general');
            Route::post('/settings/general', [SettingsController::class, 'generalUpdate'])
                ->name('settings.general.update');
            Route::get('/settings/payment', [SettingsController::class, 'payment'])
                ->name('settings.payment');
            Route::post('/settings/payment', [SettingsController::class, 'paymentUpdate'])
                ->name('settings.payment.update');

        });

        /*
        |--------------------------------------------------------------------------
        | API KEYS — super_admin only
        | (God mode: full system control)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:super_admin'])->group(function () {

            Route::get('/api-keys', [ApiKeyController::class, 'index'])
                ->name('api.keys.index');
            Route::get('/api-keys/create', [ApiKeyController::class, 'create'])
                ->name('api.keys.create');
            Route::post('/api-keys/store', [ApiKeyController::class, 'store'])
                ->name('api.keys.store');
            Route::post('/api-keys/status/{id}', [ApiKeyController::class, 'status'])
                ->name('api.keys.status');
            Route::post('/api-keys/delete/{id}', [ApiKeyController::class, 'destroy'])
                ->name('api.keys.delete');

        });

    });


// ==========================================
// AUTH ROUTES
// ==========================================

require __DIR__ . '/auth.php';