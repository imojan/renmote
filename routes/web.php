<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Front Controllers
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\Front\VehicleController as FrontVehicleController;
use App\Http\Controllers\Front\VendorController as FrontVendorController;

// User Controllers
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;

// Vendor Controllers
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\VehicleController as VendorVehicleController;
use App\Http\Controllers\Vendor\BookingController as VendorBookingController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/*
|--------------------------------------------------------------------------
| Public Routes (tanpa middleware)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/vehicles/{vehicle}', [FrontVehicleController::class, 'show'])->name('vehicles.show');
Route::get('/vendors/{vendor}', [FrontVendorController::class, 'show'])->name('vendors.show');

/*
|--------------------------------------------------------------------------
| User Routes (middleware: auth + role:user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Bookings
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{vehicle}', [UserBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/{vehicle}', [UserBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [UserBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');
});

/*
|--------------------------------------------------------------------------
| Vendor Routes (middleware: auth + role:vendor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
    
    // Vehicles
    Route::get('/vehicles', [VendorVehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [VendorVehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VendorVehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}/edit', [VendorVehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [VendorVehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VendorVehicleController::class, 'destroy'])->name('vehicles.destroy');
    
    // Bookings
    Route::get('/bookings', [VendorBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [VendorBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm', [VendorBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/complete', [VendorBookingController::class, 'complete'])->name('bookings.complete');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (middleware: auth + role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Vendors
    Route::get('/vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{vendor}', [AdminVendorController::class, 'show'])->name('vendors.show');
    Route::post('/vendors/{vendor}/verify', [AdminVendorController::class, 'verify'])->name('vendors.verify');
    Route::post('/vendors/{vendor}/unverify', [AdminVendorController::class, 'unverify'])->name('vendors.unverify');
    Route::delete('/vendors/{vendor}', [AdminVendorController::class, 'destroy'])->name('vendors.destroy');
    
    // Vehicles
    Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{vehicle}', [AdminVehicleController::class, 'show'])->name('vehicles.show');
    Route::delete('/vehicles/{vehicle}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');
    
    // Bookings
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (from Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
