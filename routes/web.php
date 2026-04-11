<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\ProductReportController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ✅ Common (All Auth Users: user/manager/admin/super_admin)
    |--------------------------------------------------------------------------
    */

    
    Route::middleware(['role:manager,admin,super_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('dashboard/profile', [ProfileController::class, 'adminProfile'])->name('dashboard.profile');
    Route::post('dashboard/profile', [ProfileController::class, 'adminProfileUpdate'])->name('dashboard.profile.update');
    Route::get('dashboard/manage-account', [ProfileController::class, 'adminAccount'])->name('dashboard.account');
    Route::post('dashboard/manage-account/password', [ProfileController::class, 'adminPasswordUpdate'])->name('dashboard.account.password');
    });
    /*
    |--------------------------------------------------------------------------
    | ✅ Manager+ (manager/admin/super_admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:manager'])->group(function () {

        // Notifications
        Route::get('dashboard/notifications/poll', [NotificationController::class, 'poll'])->name('dashboard.notifications.poll');
        Route::post('dashboard/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('dashboard.notifications.markAllRead');
        Route::post('dashboard/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('dashboard.notifications.clearAll');

        // Categories
        Route::get('dashboard/categories', [CategoryController::class, 'index'])->name('dashboard.categories.index');
        Route::get('dashboard/categories/create', [CategoryController::class, 'create'])->name('dashboard.categories.create');
        Route::post('dashboard/categories', [CategoryController::class, 'store'])->name('dashboard.categories.store');
        Route::get('dashboard/categories/{id}/edit', [CategoryController::class, 'edit'])->name('dashboard.categories.edit');
        Route::post('dashboard/categories/{id}/update', [CategoryController::class, 'update'])->name('dashboard.categories.update');
        Route::post('dashboard/categories/{id}/delete', [CategoryController::class, 'destroy'])->name('dashboard.categories.destroy');

        // Products
        Route::get('dashboard/products', [ProductController::class, 'index'])->name('dashboard.products.index');
        Route::get('dashboard/products/create', [ProductController::class, 'create'])->name('dashboard.products.create');
        Route::post('dashboard/products', [ProductController::class, 'store'])->name('dashboard.products.store');
        Route::get('dashboard/products/{id}/edit', [ProductController::class, 'edit'])->name('dashboard.products.edit');
        Route::post('dashboard/products/{id}/update', [ProductController::class, 'update'])->name('dashboard.products.update');
        Route::post('dashboard/products/{id}/delete', [ProductController::class, 'destroy'])->name('dashboard.products.destroy');

        // Orders
        Route::get('dashboard/orders', [OrderController::class, 'index'])->name('dashboard.orders.index');
        Route::get('dashboard/orders/pending', [OrderController::class, 'pending'])->name('dashboard.orders.pending');
        Route::get('dashboard/orders/completed', [OrderController::class, 'completed'])->name('dashboard.orders.completed');
        Route::get('dashboard/orders/{order_number}', [OrderController::class, 'show'])->name('dashboard.orders.show');

        // Customers
        Route::get('dashboard/customers', [CustomerController::class, 'index'])->name('dashboard.customers.index');
        Route::get('dashboard/customers/{id}', [CustomerController::class, 'show'])->name('dashboard.customers.show');

        // Marketing (Coupons)
        Route::get('dashboard/coupons', [CouponController::class, 'index'])->name('dashboard.coupons.index');
        Route::get('dashboard/coupons/create', [CouponController::class, 'create'])->name('dashboard.coupons.create');
        Route::post('dashboard/coupons', [CouponController::class, 'store'])->name('dashboard.coupons.store');
        Route::get('dashboard/coupons/{id}/edit', [CouponController::class, 'edit'])->name('dashboard.coupons.edit');
        Route::post('dashboard/coupons/{id}/update', [CouponController::class, 'update'])->name('dashboard.coupons.update');
        Route::post('dashboard/coupons/{id}/delete', [CouponController::class, 'destroy'])->name('dashboard.coupons.destroy');
        Route::post('dashboard/coupons/{id}/toggle', [CouponController::class, 'toggle'])->name('dashboard.coupons.toggle');

        // Shipping
        Route::get('dashboard/shipping', [ShippingController::class, 'index'])->name('dashboard.shipping.index');
        Route::get('dashboard/shipping/create', [ShippingController::class, 'create'])->name('dashboard.shipping.create');
        Route::post('dashboard/shipping', [ShippingController::class, 'store'])->name('dashboard.shipping.store');
        Route::get('dashboard/shipping/{id}/edit', [ShippingController::class, 'edit'])->name('dashboard.shipping.edit');
        Route::post('dashboard/shipping/{id}/update', [ShippingController::class, 'update'])->name('dashboard.shipping.update');
        Route::post('dashboard/shipping/{id}/toggle', [ShippingController::class, 'toggle'])->name('dashboard.shipping.toggle');
        Route::post('dashboard/shipping/{id}/delete', [ShippingController::class, 'destroy'])->name('dashboard.shipping.destroy');

        // Inventory
        Route::get('dashboard/inventory', [InventoryController::class, 'index'])->name('dashboard.inventory.index');
        Route::post('dashboard/inventory/{id}', [InventoryController::class, 'updateStock'])->name('dashboard.inventory.update');
        Route::get('dashboard/inventory/logs', [InventoryController::class, 'logs'])->name('dashboard.inventory.logs');

        // Reports
        Route::get('dashboard/reports/sales', [SalesReportController::class, 'index'])->name('dashboard.reports.sales');
        Route::get('dashboard/reports/products', [ProductReportController::class, 'index'])->name('dashboard.reports.products');
    });

    /*
    |--------------------------------------------------------------------------
    | ✅ Admin+ (admin/super_admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // Settings
        Route::get('dashboard/settings', [SettingController::class, 'index'])->name('dashboard.settings.index');

        Route::get('dashboard/settings/general', [SettingController::class, 'general'])->name('dashboard.settings.general');
        Route::post('dashboard/settings/general', [SettingController::class, 'updateGeneral'])->name('dashboard.settings.general.update');

        Route::get('dashboard/settings/payment', [SettingController::class, 'payment'])->name('dashboard.settings.payment');
        Route::post('dashboard/settings/payment', [SettingController::class, 'updatePayment'])->name('dashboard.settings.payment.update');

        Route::get('dashboard/settings/inventory', [SettingController::class, 'inventory'])->name('dashboard.settings.inventory');
        Route::post('dashboard/settings/inventory', [SettingController::class, 'updateInventory'])->name('dashboard.settings.inventory.update');
    });

    /*
    |--------------------------------------------------------------------------
    | ✅ Admin & Super Admin only
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin, super_admin, manager'])->group(function () {

        // Users management
        Route::get('dashboard/user', [UserController::class, 'index'])->name('dashboard.user.index');
        Route::get('dashboard/user/staff', [UserController::class, 'staff'])->name('dashboard.user.staff');
        Route::get('dashboard/user/add', [UserController::class, 'add'])->name('dashboard.user.add');
        Route::get('dashboard/user/view/{slug}', [UserController::class, 'show'])->name('dashboard.user.show');
        Route::get('dashboard/user/edit/{slug}', [UserController::class, 'edit'])->name('dashboard.user.edit');
        Route::post('dashboard/user/update/{slug}', [UserController::class, 'update'])->name('dashboard.user.update');
        Route::post('dashboard/user/store', [UserController::class, 'store'])->name('dashboard.user.store');
    });

});

/*
|--------------------------------------------------------------------------
| Laravel Breeze/Fortify profile routes (auth required)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
