<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ----------------------------------------------------------------------------
// Public routes
// ----------------------------------------------------------------------------

// Home page
Route::get("/", function () {
    return view("admin.auth.login");
});

// User authentication routes
Auth::routes();

// Booking routes
Route::get('/Booking_form', [BookingController::class, 'show'])->name('showform');
Route::post('/Booking_form/store', [BookingController::class, 'store'])->name('booking.store');
Route::post('/validate-coupon', action: [CouponController::class, 'validateCoupon'])->name('validate.coupon');
Route::get('/booking/confirmation/{id}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

// Rate routes
Route::get('/rates', [RateController::class, 'index'])->name('rates.index');
Route::get('/rates/create', [RateController::class, 'create'])->name('rates.create');
Route::post('/rates', [RateController::class, 'store'])->name('rates.store');
Route::get('/rates/edit', [RateController::class, 'edit'])->name('rates.edit');
Route::put('/rates/{id}', [RateController::class, 'update'])->name('rates.update');

Route::put('/frequencies/{id}', [RateController::class, 'updateFrequency'])->name('admin.frequencies.update');
Route::put('/cleaning-types/{id}', [RateController::class, 'updateCleaningType'])->name('admin.cleaning-types.update');
Route::put('/square-footages/{id}', [RateController::class, 'updateSquareFootage'])->name('admin.square-footages.update');
Route::put('/bedrooms/{id}', [RateController::class, 'updateBedroom'])->name('admin.bedrooms.update');
Route::put('/bathrooms/{id}', [RateController::class, 'updateBathroom'])->name('admin.bathrooms.update');
Route::put('/custom-options/{id}', [RateController::class, 'updateCustomOption'])->name('admin.custom-options.update');
// Delete routes for all sections
Route::delete('/frequencies/{frequency}', [RateController::class, 'destroyFrequency'])->name('frequencies.destroy');
Route::delete('/cleaning_types/{cleaningType}', [RateController::class, 'destroyCleaningType'])->name('cleaning-types.destroy');
Route::delete('/square-footages/{squareFootage}', [RateController::class, 'destroySquarefootages'])->name('square-footages.destroy');
Route::delete('/bedrooms/{bedroom}', [RateController::class, 'destroybedroom'])->name('bedrooms.destroy');
Route::delete('/bathrooms/{bathroom}', [RateController::class, 'destroybathrooms'])->name('bathrooms.destroy');
Route::delete('/custom-options/{id}', action: [RateController::class, 'destroycustomoptions'])->name('custom-options.destroy');
// ----------------------------------------------------------------------------
// Admin routes
// ----------------------------------------------------------------------------
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth routes for admins
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');

    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::get('/bookings', [CouponController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/list', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/admin/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');



    // Protected by auth:admin
    Route::middleware('auth:admin')->group(function () {

        Route::get('Dashboard', [DashboardController::class, 'index'])->name('showdashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
