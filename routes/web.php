<?php

use Illuminate\Support\Facades\Route;

// ==========================================================
// FRONTEND CONTROLLERS
// ==========================================================


use App\Http\Controllers\Frontend\SubscriberController as FrontSubscriberController;

// ==========================================================
// AUTH CONTROLLER
// ==========================================================

use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ==========================================================
// ADMIN CONTROLLERS
// ==========================================================

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
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\Admin\TourTypeController;
use App\Http\Controllers\Admin\FAQCategoryController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\ResortController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\RoomPriceController;
use App\Http\Controllers\Admin\RoomAvailabilityController;
use App\Http\Controllers\Admin\ResortBookingController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\TransportBookingController;
use App\Http\Controllers\Admin\VehicleController;

// ==========================================================
// VENDOR CONTROLLERS
// ==========================================================

use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorRoomBookingController;
use App\Http\Controllers\Vendor\VendorEarningController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorWalletController;
use App\Http\Controllers\Vendor\VendorWithdrawalController;
use App\Http\Controllers\Vendor\VendorRoomTypeController;
use App\Http\Controllers\Vendor\VendorResortController;
use App\Http\Controllers\Vendor\VendorFacilityController;
use App\Http\Controllers\Vendor\VendorRoomController;
use App\Http\Controllers\Vendor\VendorRoomPriceController;
use App\Http\Controllers\Vendor\VendorRoomAvailabilityController;
use App\Http\Controllers\Vendor\VendorResortImageController;
use App\Http\Controllers\Vendor\VendorRoomImageController;
use App\Http\Controllers\Vendor\VendorReviewController;
use App\Http\Controllers\Vendor\VendorCommissionController;
use App\Http\Controllers\Vendor\VendorReportController;
use App\Http\Controllers\Vendor\VendorInvoiceController;
use App\Http\Controllers\Vendor\VendorPaymentMethodController;
use App\Http\Controllers\Vendor\VendorVehicleController;
use App\Http\Controllers\Vendor\VendorTransportBookingController;


// ==========================================================
// PUBLIC FRONTEND
// ==========================================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/subscribe', [FrontSubscriberController::class, 'store'])
    ->name('subscriber.store');


// ==========================================================
// USER / CUSTOMER PANEL
// ==========================================================

Route::prefix('user')
    ->middleware(['auth', 'role:user'])
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('frontend.user.dashboard');
        })->name('dashboard');

        // Add customer booking/payment/history routes here
    });


// ==========================================================
// VENDOR PANEL
// ==========================================================

Route::prefix('vendor')
    ->middleware(['auth', 'role:vendor'])
    ->name('vendor.')
    ->group(function () {

        // ------------------------------------------------------
        // DASHBOARD
        // ------------------------------------------------------

        Route::get('/dashboard', [
            VendorDashboardController::class,
            'index'
        ])->name('dashboard');


        // ------------------------------------------------------
        // PROFILE
        // ------------------------------------------------------

        Route::get('/profile', [
            VendorProfileController::class,
            'index'
        ])->name('profile.index');

        Route::get('/profile/edit', [
            VendorProfileController::class,
            'edit'
        ])->name('profile.edit');

        Route::put('/profile', [
            VendorProfileController::class,
            'update'
        ])->name('profile.update');

        Route::put('/profile/password', [
            VendorProfileController::class,
            'updatePassword'
        ])->name('profile.password');


        // ------------------------------------------------------
        // REVIEWS
        // ------------------------------------------------------

        Route::get('/reviews', [
            VendorReviewController::class,
            'index'
        ])->name('reviews.index');

        Route::get('/reviews/{id}', [
            VendorReviewController::class,
            'show'
        ])->name('reviews.show');


        // ------------------------------------------------------
        // ROOM BOOKINGS
        // ------------------------------------------------------

        Route::get('/room-bookings', [
            VendorRoomBookingController::class,
            'index'
        ])->name('room-bookings.index');

        Route::get('/room-bookings/{booking}', [
            VendorRoomBookingController::class,
            'show'
        ])->name('room-bookings.show');

        Route::get('/room-bookings/{booking}/edit', [
            VendorRoomBookingController::class,
            'edit'
        ])->name('room-bookings.edit');

        Route::put('/room-bookings/{booking}', [
            VendorRoomBookingController::class,
            'update'
        ])->name('room-bookings.update');

        Route::post('/room-bookings/payments/{payment}/approve', [
            VendorRoomBookingController::class,
            'approvePayment'
        ])->name('room-bookings.payment.approve');

        Route::post('/room-bookings/payments/{payment}/reject', [
            VendorRoomBookingController::class,
            'rejectPayment'
        ])->name('room-bookings.payment.reject');

        Route::post('/room-bookings/{booking}/confirm', [
            VendorRoomBookingController::class,
            'confirm'
        ])->name('room-bookings.confirm');

        Route::post('/room-bookings/{booking}/check-in', [
            VendorRoomBookingController::class,
            'checkIn'
        ])->name('room-bookings.check-in');

        Route::post('/room-bookings/{booking}/check-out', [
            VendorRoomBookingController::class,
            'checkOut'
        ])->name('room-bookings.check-out');

        Route::post('/room-bookings/{booking}/cancel', [
            VendorRoomBookingController::class,
            'cancel'
        ])->name('room-bookings.cancel');


// ------------------------------------------------------
// TRANSPORT BOOKINGS
// ------------------------------------------------------

Route::get('/transport-bookings', [
    VendorTransportBookingController::class,
    'index'
])->name('transport-bookings.index');


Route::get('/transport-bookings/{booking}', [
    VendorTransportBookingController::class,
    'show'
])->name('transport-bookings.show');

// Payment Approve
Route::post('/transport-bookings/{booking}/payments/{payment}/approve', [
    VendorTransportBookingController::class,
    'approvePayment'
])->name('transport-bookings.payments.approve');


// Payment Reject
Route::post('/transport-bookings/{booking}/payments/{payment}/reject', [
    VendorTransportBookingController::class,
    'rejectPayment'
])->name('transport-bookings.payments.reject');


// Confirm
Route::post('/transport-bookings/{booking}/confirm', [
    VendorTransportBookingController::class,
    'confirm'
])->name('transport-bookings.confirm');


// Cancel
Route::post('/transport-bookings/{booking}/cancel', [
    VendorTransportBookingController::class,
    'cancel'
])->name('transport-bookings.cancel');


// Delete
Route::delete('/transport-bookings/{booking}', [
    VendorTransportBookingController::class,
    'destroy'
])->name('transport-bookings.destroy'); 

        // ------------------------------------------------------
        // EARNINGS
        // ------------------------------------------------------

        Route::get('/earnings', [
            VendorEarningController::class,
            'index'
        ])->name('earnings.index');


        // ------------------------------------------------------
        // COMMISSIONS
        // ------------------------------------------------------

        Route::get('/commissions', [
            VendorCommissionController::class,
            'index'
        ])->name('commissions.index');

        Route::get('/commissions/{id}', [
            VendorCommissionController::class,
            'show'
        ])->name('commissions.show');


        // ------------------------------------------------------
        // WALLET
        // ------------------------------------------------------

        Route::get('/wallet', [
            VendorWalletController::class,
            'index'
        ])->name('wallet.index');


        // ------------------------------------------------------
        // WITHDRAWALS
        // ------------------------------------------------------

        Route::get('/withdrawals', [
            VendorWithdrawalController::class,
            'index'
        ])->name('withdrawals.index');

        Route::post('/withdrawals/store', [
            VendorWithdrawalController::class,
            'store'
        ])->name('withdrawals.store');


        // ------------------------------------------------------
        // PAYMENT METHODS
        // ------------------------------------------------------

        Route::get('/payment-methods', [
            VendorPaymentMethodController::class,
            'index'
        ])->name('payment-methods.index');

        Route::post('/payment-methods', [
            VendorPaymentMethodController::class,
            'store'
        ])->name('payment-methods.store');

        Route::put('/payment-methods/{id}', [
            VendorPaymentMethodController::class,
            'update'
        ])->name('payment-methods.update');

        Route::delete('/payment-methods/{id}', [
            VendorPaymentMethodController::class,
            'destroy'
        ])->name('payment-methods.destroy');


        // ------------------------------------------------------
        // RESORT MANAGEMENT
        // ------------------------------------------------------

        Route::get('/resorts', [
            VendorResortController::class,
            'index'
        ])->name('resorts.index');

        Route::get('/resorts/create', [
            VendorResortController::class,
            'create'
        ])->name('resorts.create');

        Route::post('/resorts/store', [
            VendorResortController::class,
            'store'
        ])->name('resorts.store');

        Route::get('/resorts/edit/{slug}', [
            VendorResortController::class,
            'edit'
        ])->name('resorts.edit');

        Route::post('/resorts/update/{slug}', [
            VendorResortController::class,
            'update'
        ])->name('resorts.update');

        Route::delete('/resorts/{id}', [
            VendorResortController::class,
            'destroy'
        ])->name('resorts.delete');

        Route::delete('/resorts/images/{id}', [
            VendorResortController::class,
            'destroyImage'
        ])->name('resorts.images.destroy');


        // ------------------------------------------------------
        // FACILITIES
        // ------------------------------------------------------

        Route::get('/facilities', [
            VendorFacilityController::class,
            'index'
        ])->name('facilities.index');

        Route::get('/facilities/create', [
            VendorFacilityController::class,
            'create'
        ])->name('facilities.create');

        Route::post('/facilities/store', [
            VendorFacilityController::class,
            'store'
        ])->name('facilities.store');

        Route::get('/facilities/edit/{id}', [
            VendorFacilityController::class,
            'edit'
        ])->name('facilities.edit');

        Route::put('/facilities/update/{id}', [
            VendorFacilityController::class,
            'update'
        ])->name('facilities.update');

        Route::delete('/facilities/{id}', [
            VendorFacilityController::class,
            'destroy'
        ])->name('facilities.destroy');


        // ------------------------------------------------------
        // ROOMS
        // ------------------------------------------------------

        Route::get('/rooms', [
            VendorRoomController::class,
            'index'
        ])->name('rooms.index');

        Route::get('/rooms/create', [
            VendorRoomController::class,
            'create'
        ])->name('rooms.create');

        Route::post('/rooms', [
            VendorRoomController::class,
            'store'
        ])->name('rooms.store');

        Route::get('/rooms/{room}/edit', [
            VendorRoomController::class,
            'edit'
        ])->name('rooms.edit');

        Route::put('/rooms/{room}', [
            VendorRoomController::class,
            'update'
        ])->name('rooms.update');

        Route::delete('/rooms/{room}', [
            VendorRoomController::class,
            'destroy'
        ])->name('rooms.destroy');


        // ------------------------------------------------------
        // ROOM PRICES
        // ------------------------------------------------------

        Route::get('/rooms/{room}/prices', [
            VendorRoomPriceController::class,
            'index'
        ])->name('room-prices.index');

        Route::get('/rooms/{room}/prices/create', [
            VendorRoomPriceController::class,
            'create'
        ])->name('room-prices.create');

        Route::post('/rooms/{room}/prices', [
            VendorRoomPriceController::class,
            'store'
        ])->name('room-prices.store');

        Route::get('/room-prices/{roomPrice}/edit', [
            VendorRoomPriceController::class,
            'edit'
        ])->name('room-prices.edit');

        Route::put('/room-prices/{roomPrice}', [
            VendorRoomPriceController::class,
            'update'
        ])->name('room-prices.update');

        Route::delete('/room-prices/{roomPrice}', [
            VendorRoomPriceController::class,
            'destroy'
        ])->name('room-prices.destroy');


        // ------------------------------------------------------
        // ROOM AVAILABILITY
        // ------------------------------------------------------

        Route::get('/rooms/{room}/availability', [
            VendorRoomAvailabilityController::class,
            'index'
        ])->name('room-availabilities.index');

        Route::get('/rooms/{room}/availability/create', [
            VendorRoomAvailabilityController::class,
            'create'
        ])->name('room-availabilities.create');

        Route::post('/rooms/{room}/availability', [
            VendorRoomAvailabilityController::class,
            'store'
        ])->name('room-availabilities.store');

        Route::get('/room-availability/{availability}/edit', [
            VendorRoomAvailabilityController::class,
            'edit'
        ])->name('room-availabilities.edit');

        Route::put('/room-availability/{availability}', [
            VendorRoomAvailabilityController::class,
            'update'
        ])->name('room-availabilities.update');

        Route::delete('/room-availability/{availability}', [
            VendorRoomAvailabilityController::class,
            'destroy'
        ])->name('room-availabilities.destroy');


        // ------------------------------------------------------
        // ROOM TYPES
        // ------------------------------------------------------

        Route::get('/room-types', [
            VendorRoomTypeController::class,
            'index'
        ])->name('room-types.index');

        Route::get('/room-types/create', [
            VendorRoomTypeController::class,
            'create'
        ])->name('room-types.create');

        Route::post('/room-types', [
            VendorRoomTypeController::class,
            'store'
        ])->name('room-types.store');

        Route::get('/room-types/{roomType}/edit', [
            VendorRoomTypeController::class,
            'edit'
        ])->name('room-types.edit');

        Route::put('/room-types/{roomType}', [
            VendorRoomTypeController::class,
            'update'
        ])->name('room-types.update');

        Route::delete('/room-types/{roomType}', [
            VendorRoomTypeController::class,
            'destroy'
        ])->name('room-types.destroy');


        // ------------------------------------------------------
        // RESORT IMAGES
        // ------------------------------------------------------

        Route::get('/resorts/{resort}/images', [
            VendorResortImageController::class,
            'index'
        ])->name('resort-images.index');

        Route::post('/resorts/{resort}/images', [
            VendorResortImageController::class,
            'store'
        ])->name('resort-images.store');

        Route::post('/resort-images/{resortImage}/cover', [
            VendorResortImageController::class,
            'setCover'
        ])->name('resort-images.cover');

        Route::put('/resort-images/{resortImage}/order', [
            VendorResortImageController::class,
            'updateOrder'
        ])->name('resort-images.order');

        Route::delete('/resort-images/{resortImage}', [
            VendorResortImageController::class,
            'destroy'
        ])->name('resort-images.destroy');


        // ------------------------------------------------------
        // ROOM IMAGES
        // ------------------------------------------------------

        Route::get('/rooms/{room}/images', [
            VendorRoomImageController::class,
            'index'
        ])->name('room-images.index');

        Route::post('/rooms/{room}/images', [
            VendorRoomImageController::class,
            'store'
        ])->name('room-images.store');

        Route::post('/room-images/{image}/cover', [
            VendorRoomImageController::class,
            'setCover'
        ])->name('room-images.cover');

        Route::put('/room-images/{image}/order', [
            VendorRoomImageController::class,
            'updateOrder'
        ])->name('room-images.order');

        Route::delete('/room-images/{image}', [
            VendorRoomImageController::class,
            'destroy'
        ])->name('room-images.destroy');


        // ------------------------------------------------------
        // REPORTS
        // ------------------------------------------------------

        Route::get('/reports', [
            VendorReportController::class,
            'index'
        ])->name('reports.index');


        // ------------------------------------------------------
        // INVOICES
        // ------------------------------------------------------

        Route::get('/invoices/{booking}', [
            VendorInvoiceController::class,
            'show'
        ])->name('invoices.show');

        Route::get('/invoices/{booking}/download', [
            VendorInvoiceController::class,
            'download'
        ])->name('invoices.download');

        Route::get('/invoices/{booking}/print', [
            VendorInvoiceController::class,
            'print'
        ])->name('invoices.print');


        // ------------------------------------------------------
        // VEHICLES
        // ------------------------------------------------------

        Route::resource('vehicles', VendorVehicleController::class)
            ->except(['show']);

        Route::patch('vehicles/{vehicle}/toggle-status', [
            VendorVehicleController::class,
            'toggleStatus'
        ])->name('vehicles.toggle-status');
    });


// ==========================================================
// ADMIN PANEL
// ==========================================================

Route::prefix('admin')
    ->middleware(['auth', 'role:manager,admin,super_admin'])
    ->name('admin.')
    ->group(function () {

        // ------------------------------------------------------
        // DASHBOARD
        // ------------------------------------------------------

        Route::get('/dashboard', [
            AdminDashboardController::class,
            'index'
        ])->name('dashboard');


        // ------------------------------------------------------
        // PROFILE
        // ------------------------------------------------------

        Route::get('/profile', [
            ProfileController::class,
            'adminProfile'
        ])->name('profile');

        Route::post('/profile', [
            ProfileController::class,
            'adminProfileUpdate'
        ])->name('profile.update');

        Route::get('/manage-account', [
            ProfileController::class,
            'adminAccount'
        ])->name('account');

        Route::post('/manage-account/password', [
            ProfileController::class,
            'adminPasswordUpdate'
        ])->name('account.password');


        // ------------------------------------------------------
        // NOTIFICATIONS
        // ------------------------------------------------------

        Route::get('/notifications/poll', [
            NotificationController::class,
            'poll'
        ])->name('notifications.poll');

        Route::post('/notifications/mark-all-read', [
            NotificationController::class,
            'markAllRead'
        ])->name('notifications.markAllRead');

        Route::post('/notifications/clear-all', [
            NotificationController::class,
            'clearAll'
        ])->name('notifications.clearAll');


        // ------------------------------------------------------
        // TOUR PACKAGES
        // ------------------------------------------------------

        Route::get('/tour-packages', [
            TourPackageController::class,
            'index'
        ])->name('tours.index');

        Route::get('/tour-packages/add', [
            TourPackageController::class,
            'create'
        ])->name('tours.create');

        Route::post('/tour-packages/store', [
            TourPackageController::class,
            'store'
        ])->name('tours.store');

        Route::get('/tours/view/{slug}', [
            TourPackageController::class,
            'show'
        ])->name('tours.show');

        Route::get('/tours/edit/{slug}', [
            TourPackageController::class,
            'edit'
        ])->name('tours.edit');

        Route::post('/tours/update/{slug}', [
            TourPackageController::class,
            'update'
        ])->name('tours.update');

        Route::post('/tours/delete/{id}', [
            TourPackageController::class,
            'destroy'
        ])->name('tours.delete');

        Route::get('/tours/{id}/modal-data', [
            TourPackageController::class,
            'modalData'
        ])->name('tours.modal-data');

        Route::post('/tours/{id}/approve', [
            TourPackageController::class,
            'approve'
        ])->name('tours.approve');

        Route::post('/tours/{id}/reject', [
            TourPackageController::class,
            'reject'
        ])->name('tours.reject');


        // ------------------------------------------------------
        // TOUR DATES
        // ------------------------------------------------------

        Route::get('/tour-dates', [
            TourDateController::class,
            'index'
        ])->name('tour.dates.index');

        Route::get('/tour-dates/add', [
            TourDateController::class,
            'create'
        ])->name('tour.dates.create');

        Route::post('/tour-dates/store', [
            TourDateController::class,
            'store'
        ])->name('tour.dates.store');


        // ------------------------------------------------------
        // DESTINATIONS
        // ------------------------------------------------------

        Route::get('/destinations', [
            DestinationController::class,
            'index'
        ])->name('destinations.index');

        Route::get('/destinations/create', [
            DestinationController::class,
            'create'
        ])->name('destinations.create');

        Route::post('/destinations/store', [
            DestinationController::class,
            'store'
        ])->name('destinations.store');

        Route::get('/destinations/edit/{slug}', [
            DestinationController::class,
            'edit'
        ])->name('destinations.edit');

        Route::post('/destinations/update/{slug}', [
            DestinationController::class,
            'update'
        ])->name('destinations.update');

        Route::post('/destinations/delete/{id}', [
            DestinationController::class,
            'destroy'
        ])->name('destinations.delete');


        // ------------------------------------------------------
        // TOUR TYPES
        // ------------------------------------------------------

        Route::resource('tour-types', TourTypeController::class);

        Route::get('/tour-types/{id}/modal-data', [
            TourTypeController::class,
            'modalData'
        ])->name('tour-types.modal-data');


        // ------------------------------------------------------
        // BOOKINGS
        // ------------------------------------------------------

        Route::get('/bookings/pending', [
            BookingController::class,
            'pending'
        ])->name('bookings.pending');

        Route::get('/bookings/confirmed', [
            BookingController::class,
            'confirmed'
        ])->name('bookings.confirmed');

        Route::get('/bookings/view/{id}', [
            BookingController::class,
            'show'
        ])->name('bookings.show');

        Route::post('/bookings/{id}/confirm', [
            BookingController::class,
            'confirm'
        ])->name('bookings.confirm');

        Route::post('/bookings/{id}/cancel', [
            BookingController::class,
            'cancel'
        ])->name('bookings.cancel');


        // ------------------------------------------------------
        // TRAVELERS
        // ------------------------------------------------------

        Route::get('/travelers', [
            TravelerController::class,
            'index'
        ])->name('travelers.index');

        Route::get('/travelers/add', [
            TravelerController::class,
            'create'
        ])->name('travelers.create');

        Route::post('/travelers/store', [
            TravelerController::class,
            'store'
        ])->name('travelers.store');


        // ------------------------------------------------------
        // REPORTS
        // ------------------------------------------------------

        Route::get('/reports/bookings', [
            BookingReportController::class,
            'bookingReport'
        ])->name('reports.bookings');

        Route::get('/reports/revenue', [
            BookingReportController::class,
            'revenueReport'
        ])->name('reports.revenue');


        // ------------------------------------------------------
        // TRANSACTIONS
        // ------------------------------------------------------

        Route::get('/transactions', [
            TransactionController::class,
            'index'
        ])->name('transactions.index');

        Route::get('/transactions/{id}', [
            TransactionController::class,
            'show'
        ])->name('transactions.show');

        Route::post('/transactions/{id}/status', [
            TransactionController::class,
            'updateStatus'
        ])->name('transactions.status');

        Route::post('/transactions/{id}/delete', [
            TransactionController::class,
            'destroy'
        ])->name('transactions.delete');

        Route::get('/transactions/{id}/invoice', [
            TransactionController::class,
            'invoice'
        ])->name('transactions.invoice');


        // ------------------------------------------------------
        // PAYMENT METHODS
        // ------------------------------------------------------

        Route::get('/payment-methods', [
            PaymentMethodController::class,
            'index'
        ])->name('payment_methods.index');

        Route::get('/payment-methods/create', [
            PaymentMethodController::class,
            'create'
        ])->name('payment_methods.create');

        Route::post('/payment-methods', [
            PaymentMethodController::class,
            'store'
        ])->name('payment_methods.store');

        Route::get('/payment-methods/{id}/edit', [
            PaymentMethodController::class,
            'edit'
        ])->name('payment_methods.edit');

        Route::post('/payment-methods/{id}/update', [
            PaymentMethodController::class,
            'update'
        ])->name('payment_methods.update');

        Route::get('/payment-methods/{id}/delete', [
            PaymentMethodController::class,
            'destroy'
        ])->name('payment_methods.delete');


        // ------------------------------------------------------
        // COUPONS
        // ------------------------------------------------------

        Route::get('/coupons', [
            CouponController::class,
            'index'
        ])->name('coupons.index');

        Route::get('/coupons/add', [
            CouponController::class,
            'create'
        ])->name('coupons.create');

        Route::post('/coupons/store', [
            CouponController::class,
            'store'
        ])->name('coupons.store');

        Route::get('/coupons/edit/{slug}', [
            CouponController::class,
            'edit'
        ])->name('coupons.edit');

        Route::post('/coupons/update/{slug}', [
            CouponController::class,
            'update'
        ])->name('coupons.update');

        Route::post('/coupons/delete/{id}', [
            CouponController::class,
            'destroy'
        ])->name('coupons.delete');


        // ------------------------------------------------------
        // TRANSPORT BOOKINGS
        // ------------------------------------------------------

        Route::get('/transport-bookings', [
            TransportBookingController::class,
            'index'
        ])->name('transport-bookings.index');

        Route::put('/transport-bookings/{transportBooking}', [
            TransportBookingController::class,
            'update'
        ])->name('transport-bookings.update');

        Route::delete('/transport-bookings/{transportBooking}', [
            TransportBookingController::class,
            'destroy'
        ])->name('transport-bookings.destroy');


        // ------------------------------------------------------
        // COMMISSIONS
        // ------------------------------------------------------

        Route::get('/commissions', [
            CommissionController::class,
            'index'
        ])->name('commissions.index');

        Route::get('/commissions/{id}', [
            CommissionController::class,
            'show'
        ])->name('commissions.show');


        // ------------------------------------------------------
        // ADS
        // ------------------------------------------------------

        Route::get('/ads', [
            AdsController::class,
            'index'
        ])->name('ads.index');

        Route::get('/ads/create', [
            AdsController::class,
            'create'
        ])->name('ads.create');

        Route::post('/ads/store', [
            AdsController::class,
            'store'
        ])->name('ads.store');

        Route::get('/ads/edit/{id}', [
            AdsController::class,
            'edit'
        ])->name('ads.edit');

        Route::post('/ads/update/{id}', [
            AdsController::class,
            'update'
        ])->name('ads.update');

        Route::post('/ads/delete/{id}', [
            AdsController::class,
            'destroy'
        ])->name('ads.delete');


        // ------------------------------------------------------
        // CONTACT MESSAGES
        // ------------------------------------------------------

        Route::get('/contact-messages', [
            ContactMessageController::class,
            'index'
        ])->name('contact.index');

        Route::get('/contact-messages/{id}', [
            ContactMessageController::class,
            'show'
        ])->name('contact.show');

        Route::post('/contact-messages/read/{id}', [
            ContactMessageController::class,
            'markRead'
        ])->name('contact.read');

        Route::post('/contact-messages/delete/{id}', [
            ContactMessageController::class,
            'destroy'
        ])->name('contact.delete');


        // ------------------------------------------------------
        // SUBSCRIBERS
        // ------------------------------------------------------

        Route::get('/subscribers', [
            SubscriberController::class,
            'index'
        ])->name('subscribers.index');

        Route::post('/subscribers/delete/{id}', [
            SubscriberController::class,
            'destroy'
        ])->name('subscribers.delete');


        // ------------------------------------------------------
        // BLOGS
        // ------------------------------------------------------

        Route::get('/blogs', [
            BlogController::class,
            'index'
        ])->name('blogs.index');

        Route::get('/blogs/create', [
            BlogController::class,
            'create'
        ])->name('blogs.create');

        Route::post('/blogs/store', [
            BlogController::class,
            'store'
        ])->name('blogs.store');

        Route::get('/blogs/show/{slug}', [
            BlogController::class,
            'show'
        ])->name('blogs.show');

        Route::get('/blogs/edit/{slug}', [
            BlogController::class,
            'edit'
        ])->name('blogs.edit');

        Route::post('/blogs/update/{slug}', [
            BlogController::class,
            'update'
        ])->name('blogs.update');

        Route::post('/blogs/delete/{slug}', [
            BlogController::class,
            'destroy'
        ])->name('blogs.delete');


        // ------------------------------------------------------
        // BLOG CATEGORIES
        // ------------------------------------------------------

        Route::get('/blog-categories', [
            BlogCategoryController::class,
            'index'
        ])->name('blog.categories.index');

        Route::post('/blog-categories/store', [
            BlogCategoryController::class,
            'store'
        ])->name('blog.categories.store');

        Route::get('/blog-categories/edit/{id}', [
            BlogCategoryController::class,
            'edit'
        ])->name('blog.categories.edit');

        Route::post('/blog-categories/update/{id}', [
            BlogCategoryController::class,
            'update'
        ])->name('blog.categories.update');

        Route::post('/blog-categories/delete/{id}', [
            BlogCategoryController::class,
            'destroy'
        ])->name('blog.categories.delete');


        // ------------------------------------------------------
        // GALLERY
        // ------------------------------------------------------

        Route::get('/gallery', [
            GalleryController::class,
            'index'
        ])->name('gallery.index');

        Route::get('/gallery/create', [
            GalleryController::class,
            'create'
        ])->name('gallery.create');

        Route::post('/gallery/store', [
            GalleryController::class,
            'store'
        ])->name('gallery.store');

        Route::post('/gallery/delete/{id}', [
            GalleryController::class,
            'destroy'
        ])->name('gallery.delete');


        // ------------------------------------------------------
        // TESTIMONIALS
        // ------------------------------------------------------

        Route::get('/testimonials', [
            TestimonialController::class,
            'index'
        ])->name('testimonials.index');

        Route::get('/testimonials/create', [
            TestimonialController::class,
            'create'
        ])->name('testimonials.create');

        Route::post('/testimonials/store', [
            TestimonialController::class,
            'store'
        ])->name('testimonials.store');

        Route::post('/testimonials/delete/{id}', [
            TestimonialController::class,
            'destroy'
        ])->name('testimonials.delete');


        // ------------------------------------------------------
        // SEO SETTINGS
        // ------------------------------------------------------

        Route::get('/seo-settings', [
            SeoSettingController::class,
            'index'
        ])->name('seo.index');

        Route::get('/seo-settings/create', [
            SeoSettingController::class,
            'create'
        ])->name('seo.create');

        Route::post('/seo-settings/store', [
            SeoSettingController::class,
            'store'
        ])->name('seo.store');

        Route::get('/seo-settings/edit/{id}', [
            SeoSettingController::class,
            'edit'
        ])->name('seo.edit');

        Route::post('/seo-settings/update/{id}', [
            SeoSettingController::class,
            'update'
        ])->name('seo.update');

        Route::post('/seo-settings/delete/{id}', [
            SeoSettingController::class,
            'destroy'
        ])->name('seo.delete');


        // ------------------------------------------------------
        // WITHDRAWALS
        // ------------------------------------------------------

        Route::get('/withdrawals', [
            AdminWithdrawalController::class,
            'index'
        ])->name('withdrawals.index');

        Route::get('/withdrawals/{id}', [
            AdminWithdrawalController::class,
            'show'
        ])->name('withdrawals.show');

        Route::post('/withdrawals/{id}/approve', [
            AdminWithdrawalController::class,
            'approve'
        ])->name('withdrawals.approve');

        Route::post('/withdrawals/{id}/reject', [
            AdminWithdrawalController::class,
            'reject'
        ])->name('withdrawals.reject');


        // ------------------------------------------------------
        // USER MANAGEMENT
        // ------------------------------------------------------

        Route::middleware(['role:admin,super_admin,manager'])->group(function () {

            Route::get('/users', [
                UserController::class,
                'index'
            ])->name('users.index');

            Route::get('/users/staff', [
                UserController::class,
                'staff'
            ])->name('users.staff');

            Route::get('/users/add', [
                UserController::class,
                'add'
            ])->name('users.add');

            Route::post('/users/store', [
                UserController::class,
                'store'
            ])->name('users.store');

            Route::get('/users/view/{slug}', [
                UserController::class,
                'show'
            ])->name('users.show');

            Route::get('/users/edit/{slug}', [
                UserController::class,
                'edit'
            ])->name('users.edit');

            Route::put('/users/update/{slug}', [
                UserController::class,
                'update'
            ])->name('users.update');

            Route::delete('/users/delete/{id}', [
                UserController::class,
                'destroy'
            ])->name('users.delete');
        });


        // ------------------------------------------------------
        // VENDOR MANAGEMENT
        // ------------------------------------------------------

        Route::middleware(['role:admin,super_admin,manager'])->group(function () {

            Route::get('/vendors', [
                VendorController::class,
                'index'
            ])->name('vendors.index');

            Route::put('/vendors/{id}', [
                VendorController::class,
                'update'
            ])->name('vendors.update');

            Route::post('/vendors/{id}/approve', [
                VendorController::class,
                'approve'
            ])->name('vendors.approve');

            Route::post('/vendors/{id}/reject', [
                VendorController::class,
                'reject'
            ])->name('vendors.reject');

            Route::delete('/vendors/{id}', [
                VendorController::class,
                'destroy'
            ])->name('vendors.destroy');
        });


        // ------------------------------------------------------
        // SYSTEM SETTINGS
        // ------------------------------------------------------

        Route::middleware(['role:admin,super_admin'])->group(function () {

            Route::get('/settings', [
                SettingsController::class,
                'index'
            ])->name('settings.index');

            Route::get('/settings/general', [
                SettingsController::class,
                'general'
            ])->name('settings.general');

            Route::post('/settings/general', [
                SettingsController::class,
                'generalUpdate'
            ])->name('settings.general.update');

            Route::get('/settings/payment', [
                SettingsController::class,
                'payment'
            ])->name('settings.payment');

            Route::post('/settings/payment', [
                SettingsController::class,
                'paymentUpdate'
            ])->name('settings.payment.update');
        });


        // ------------------------------------------------------
        // API KEYS — SUPER ADMIN ONLY
        // ------------------------------------------------------

        Route::middleware(['role:super_admin'])->group(function () {

            Route::get('/api-keys', [
                ApiKeyController::class,
                'index'
            ])->name('api.keys.index');

            Route::get('/api-keys/create', [
                ApiKeyController::class,
                'create'
            ])->name('api.keys.create');

            Route::post('/api-keys/store', [
                ApiKeyController::class,
                'store'
            ])->name('api.keys.store');

            Route::post('/api-keys/status/{id}', [
                ApiKeyController::class,
                'status'
            ])->name('api.keys.status');

            Route::post('/api-keys/delete/{id}', [
                ApiKeyController::class,
                'destroy'
            ])->name('api.keys.delete');
        });


        // ------------------------------------------------------
        // ROOM TYPES
        // ------------------------------------------------------

        Route::resource('room-types', RoomTypeController::class);


        // ------------------------------------------------------
        // ROOMS
        // ------------------------------------------------------

        Route::resource('rooms', RoomController::class);

        Route::delete('/room-gallery/{id}', [
            RoomController::class,
            'deleteGalleryImage'
        ])->name('rooms.gallery.delete');

        Route::post('/rooms/{room}/toggle-status', [
            RoomController::class,
            'toggleStatus'
        ])->name('rooms.toggle.status');

        Route::get('/rooms-by-resort/{id}', [
            RoomController::class,
            'getRoomsByResort'
        ])->name('rooms.by.resort');


        // ------------------------------------------------------
        // ROOM PRICES
        // ------------------------------------------------------

        Route::get('/room-prices/get-rooms/{resort}', [
            RoomPriceController::class,
            'getRoomsByResort'
        ])->name('room-prices.getRooms');

        Route::resource('room-prices', RoomPriceController::class);


        // ------------------------------------------------------
        // ROOM AVAILABILITIES
        // ------------------------------------------------------

        Route::resource(
            'room-availabilities',
            RoomAvailabilityController::class
        );

        Route::get('/room-availabilities/get-rooms/{resort}', [
            RoomAvailabilityController::class,
            'getRooms'
        ])->name('room-availabilities.getRooms');


        // ------------------------------------------------------
        // RESORTS
        // ------------------------------------------------------

        Route::get('/resorts', [
            ResortController::class,
            'index'
        ])->name('resorts.index');

        Route::get('/resorts/create', [
            ResortController::class,
            'create'
        ])->name('resorts.create');

        Route::post('/resorts/store', [
            ResortController::class,
            'store'
        ])->name('resorts.store');

        Route::get('/resorts/edit/{slug}', [
            ResortController::class,
            'edit'
        ])->name('resorts.edit');

        Route::post('/resorts/update/{slug}', [
            ResortController::class,
            'update'
        ])->name('resorts.update');

        Route::post('/resorts/delete/{id}', [
            ResortController::class,
            'destroy'
        ])->name('resorts.delete');


        // ------------------------------------------------------
        // RESORT BOOKINGS
        // ------------------------------------------------------

        Route::resource(
            'resort-bookings',
            ResortBookingController::class
        );

        Route::get('/resort-bookings/get-rooms/{resort}', [
            ResortBookingController::class,
            'getRooms'
        ])->name('resort-bookings.rooms');

        Route::get('/resort-bookings/{booking}/details', [
            ResortBookingController::class,
            'details'
        ])->name('resort-bookings.details');

        Route::post('/resort-bookings/{booking}/change-status', [
            ResortBookingController::class,
            'changeStatus'
        ])->name('resort-bookings.change-status');

        Route::post('/resort-bookings/get-price', [
            ResortBookingController::class,
            'getPrice'
        ])->name('resort-bookings.getPrice');

        Route::post('/resort-bookings/{booking}/payment-status', [
            ResortBookingController::class,
            'paymentStatus'
        ])->name('resort-bookings.payment-status');


        // ------------------------------------------------------
        // FACILITIES
        // ------------------------------------------------------

        Route::resource(
            'facilities',
            FacilityController::class
        );


        // ------------------------------------------------------
        // FAQ CATEGORIES
        // ------------------------------------------------------

        Route::get('/faq-categories', [
            FAQCategoryController::class,
            'index'
        ])->name('faq.categories.index');

        Route::post('/faq-categories/store', [
            FAQCategoryController::class,
            'store'
        ])->name('faq.categories.store');

        Route::post('/faq-categories/update/{id}', [
            FAQCategoryController::class,
            'update'
        ])->name('faq.categories.update');

        Route::post('/faq-categories/delete/{id}', [
            FAQCategoryController::class,
            'destroy'
        ])->name('faq.categories.delete');


        // ------------------------------------------------------
        // FAQS
        // ------------------------------------------------------

        Route::get('/faqs', [
            FAQController::class,
            'index'
        ])->name('faqs.index');

        Route::post('/faqs/store', [
            FAQController::class,
            'store'
        ])->name('faqs.store');

        Route::get('/faqs/{id}/edit', [
            FAQController::class,
            'edit'
        ])->name('faqs.edit');

        Route::post('/faqs/update/{id}', [
            FAQController::class,
            'update'
        ])->name('faqs.update');

        Route::post('/faqs/delete/{id}', [
            FAQController::class,
            'destroy'
        ])->name('faqs.delete');


        // ------------------------------------------------------
        // POLICIES
        // ------------------------------------------------------

        Route::get('/policies', [
            PolicyController::class,
            'index'
        ])->name('policies.index');

        Route::post('/policies', [
            PolicyController::class,
            'update'
        ])->name('policies.update');


        // ------------------------------------------------------
        // TEAM MEMBERS
        // ------------------------------------------------------

        Route::resource(
            'team-members',
            TeamMemberController::class
        );

        Route::post('/team-members/{teamMember}/status', [
            TeamMemberController::class,
            'toggleStatus'
        ])->name('team-members.toggle-status');


        // ------------------------------------------------------
        // TRANSPORT VEHICLES
        // ------------------------------------------------------

Route::get('/transport-vehicles', [
    VehicleController::class,
    'index'
])->name('transport-vehicles.index');

Route::put('/transport-vehicles/{vehicle}', [
    VehicleController::class,
    'update'
])->name('transport-vehicles.update');

Route::post('/transport-vehicles/{vehicle}/approve', [
    VehicleController::class,
    'approve'
])->name('transport-vehicles.approve');

Route::post('/transport-vehicles/{vehicle}/reject', [
    VehicleController::class,
    'reject'
])->name('transport-vehicles.reject');

Route::post('/transport-vehicles/{vehicle}/activate', [
    VehicleController::class,
    'activate'
])->name('transport-vehicles.activate');

Route::post('/transport-vehicles/{vehicle}/deactivate', [
    VehicleController::class,
    'deactivate'
])->name('transport-vehicles.deactivate');

Route::delete('/transport-vehicles/{vehicle}', [
    VehicleController::class,
    'destroy'
])->name('transport-vehicles.destroy');

    });


// ==========================================================
// DEFAULT AUTH ROUTES
// ==========================================================

require __DIR__ . '/auth.php';

