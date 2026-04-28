<?php

use App\Http\Controllers\Api\User\ProfileController as UserProfileController;
use App\Http\Controllers\Api\Vendor\ProfileController as VendorProfileController;
use App\Http\Controllers\Api\Vendor\DocumentController as VendorDocumentController;
use App\Http\Controllers\Webhook\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Route API dengan autentikasi session (Breeze).
| Prefix /api sudah otomatis diberikan oleh framework.
|
*/

Route::post('/midtrans/notifications', [MidtransWebhookController::class, 'handle'])
    ->name('api.midtrans.notifications');

Route::middleware(['web', 'auth'])->group(function () {

    // ─── User Profile ────────────────────────────────────────
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::put('/user/profile', [UserProfileController::class, 'update']);

    // ─── Vendor Profile ──────────────────────────────────────
    Route::get('/vendor/profile', [VendorProfileController::class, 'show']);

    // ─── Vendor Documents ────────────────────────────────────
    Route::post('/vendor/documents', [VendorDocumentController::class, 'store']);
});
