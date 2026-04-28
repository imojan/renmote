<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentMediaController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Front Controllers
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\Front\ArticleController as FrontArticleController;
use App\Http\Controllers\Front\VehicleController as FrontVehicleController;
use App\Http\Controllers\Front\VendorController as FrontVendorController;
use App\Http\Controllers\ChatController;

// User Controllers
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\WishlistController;

// Vendor Controllers
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\VehicleController as VendorVehicleController;
use App\Http\Controllers\Vendor\BookingController as VendorBookingController;
use App\Http\Controllers\Vendor\VendorRegistrationController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/*
|--------------------------------------------------------------------------
| Public Routes (tanpa middleware)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{categorySlug}', [SearchController::class, 'index'])->name('search.category');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/vehicles/{vehicle}', [FrontVehicleController::class, 'show'])->name('vehicles.show');
Route::get('/vendors/{vendor}', [FrontVendorController::class, 'show'])->name('vendors.show');
Route::get('/articles', [FrontArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [FrontArticleController::class, 'show'])->name('articles.show');
Route::view('/cara-sewa', 'front.rent-guide')->name('rent.guide');
Route::view('/syarat-ketentuan-sewa', 'front.rental-terms')->name('rent.terms');

/*
|--------------------------------------------------------------------------
| User Routes (middleware: auth + role:user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Account
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::patch('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/address', [AccountController::class, 'storeAddress'])->name('account.address.store');
    Route::patch('/account/address/{address}', [AccountController::class, 'updateAddress'])->name('account.address.update');
    Route::delete('/account/address/{address}', [AccountController::class, 'destroyAddress'])->name('account.address.destroy');
    Route::post('/account/address/{address}/default', [AccountController::class, 'setDefaultAddress'])->name('account.address.default');
    Route::post('/account/documents', [AccountController::class, 'updateDocuments'])->name('account.documents.update');
    Route::delete('/account/documents/{type}', [AccountController::class, 'destroyDocument'])->name('account.documents.destroy');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::delete('/account', [AccountController::class, 'destroyAccount'])->name('account.destroy');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/vehicles/{vehicle}/toggle', [WishlistController::class, 'toggleVehicle'])->name('wishlist.vehicles.toggle');
    Route::post('/wishlist/vendors/{vendor}/toggle', [WishlistController::class, 'toggleVendor'])->name('wishlist.vendors.toggle');
    
    // Bookings
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{vehicle}', [UserBookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{vehicle}/check-availability', [UserBookingController::class, 'checkAvailability'])->name('bookings.checkAvailability');
    Route::get('/bookings/{vehicle}/confirmation', [UserBookingController::class, 'confirmation'])->name('bookings.confirmation');
    Route::post('/bookings/{vehicle}', [UserBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/payment', [UserBookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/{booking}/payment/confirm', [UserBookingController::class, 'confirmPayment'])->name('bookings.payment.confirm');
    Route::post('/bookings/{booking}/payment/retry', [UserBookingController::class, 'retryPayment'])->name('bookings.payment.retry');
    Route::post('/bookings/{booking}/payment/midtrans/finish', [UserBookingController::class, 'syncMidtransResult'])->name('bookings.payment.midtrans.finish');
    Route::get('/bookings/{booking}/payment-proof', [UserBookingController::class, 'paymentProof'])->name('bookings.payment.proof');
    Route::post('/bookings/{booking}/payment-proof', [UserBookingController::class, 'storePaymentProof'])->name('bookings.payment.proof.store');
    Route::get('/bookings/{booking}/invoice', [UserBookingController::class, 'invoice'])->name('bookings.invoice');
    Route::get('/bookings/{booking}/invoice/download', [UserBookingController::class, 'downloadInvoicePdf'])->name('bookings.invoice.download');
    Route::get('/bookings/{id}', [UserBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
});

/*
|--------------------------------------------------------------------------
| Vendor Routes (middleware: auth + role:vendor)
|--------------------------------------------------------------------------
*/
// Vendor Registration (auth only, no role check — user registers as vendor)
Route::middleware('auth')->get('/vendor/register', [VendorRegistrationController::class, 'create'])->name('vendor.register');
Route::middleware('auth')->post('/vendor/register', [VendorRegistrationController::class, 'store'])->name('vendor.register.store');

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
    Route::get('/bookings/export', [VendorBookingController::class, 'export'])->name('bookings.export');
    Route::get('/bookings/{booking}', [VendorBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/payment-proof/approve', [VendorBookingController::class, 'approvePaymentProof'])->name('bookings.paymentProof.approve');
    Route::post('/bookings/{booking}/payment-proof/reject', [VendorBookingController::class, 'rejectPaymentProof'])->name('bookings.paymentProof.reject');
    Route::post('/bookings/{booking}/confirm', [VendorBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/reject', [VendorBookingController::class, 'reject'])->name('bookings.reject');
    Route::post('/bookings/{booking}/complete', [VendorBookingController::class, 'complete'])->name('bookings.complete');
});

/*
|--------------------------------------------------------------------------
| Chat Routes (middleware: auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/conversations', [ChatController::class, 'conversations'])->name('conversations');
    Route::post('/start/vendor/{vendor}', [ChatController::class, 'startWithVendor'])->name('start.vendor');
    Route::get('/conversations/{conversation}/messages', [ChatController::class, 'messages'])->name('messages');
    Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('send');
    Route::post('/conversations/{conversation}/read', [ChatController::class, 'markAsRead'])->name('read');
    Route::get('/unread', [ChatController::class, 'unread'])->name('unread');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (middleware: auth + role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Settings (placeholder until settings module is implemented)
    Route::get('/settings', function () {
        return redirect()->route('admin.dashboard')->with('info', 'Halaman pengaturan belum tersedia.');
    })->name('settings.index');

    // Documents
    Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
    Route::patch('/documents/vendors/{document}', [AdminDocumentController::class, 'updateVendorDocument'])->name('documents.vendors.update');
    Route::patch('/documents/users/{document}', [AdminDocumentController::class, 'updateUserDocument'])->name('documents.users.update');

    // Articles
    Route::resource('articles', AdminArticleController::class)->except('show');
    
    // Vendors
    Route::get('/vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{vendor}', [AdminVendorController::class, 'show'])->name('vendors.show');
    Route::post('/vendors/{vendor}/verify', [AdminVendorController::class, 'verify'])->name('vendors.verify');
    Route::post('/vendors/{vendor}/unverify', [AdminVendorController::class, 'unverify'])->name('vendors.unverify');
    Route::delete('/vendors/{vendor}', [AdminVendorController::class, 'destroy'])->name('vendors.destroy');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Vendor Document Review (generate signed temp URL)
    Route::get('/vendor-documents/{document}/review', [VendorRegistrationController::class, 'reviewDocument'])->name('vendor-documents.review');
    
    // Vehicles
    Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{vehicle}', [AdminVehicleController::class, 'show'])->name('vehicles.show');
    Route::delete('/vehicles/{vehicle}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');
    
    // Bookings
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/payment-proof/approve', [AdminBookingController::class, 'approvePaymentProof'])->name('bookings.paymentProof.approve');
    Route::post('/bookings/{booking}/payment-proof/reject', [AdminBookingController::class, 'rejectPaymentProof'])->name('bookings.paymentProof.reject');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
});

// Signed URL route for serving vendor documents (no auth — validated by signature)
Route::get('/vendor-documents/{document}/serve', [VendorRegistrationController::class, 'serveDocument'])
    ->name('vendor.document.serve')
    ->middleware('signed');

/*
|--------------------------------------------------------------------------
| Profile Routes (from Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    Route::get('/documents/vendor/{document}/media', [DocumentMediaController::class, 'vendor'])->name('documents.vendor.media');
    Route::get('/documents/user/{document}/media', [DocumentMediaController::class, 'user'])->name('documents.user.media');
    Route::get('/documents/payment/{payment}/proof', [DocumentMediaController::class, 'paymentProof'])->name('documents.payment.proof.media');

    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'vendor' => redirect()->route('vendor.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
