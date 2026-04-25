<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\UserDocument;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentMediaController extends Controller
{
    /**
     * Tampilkan file dokumen vendor untuk admin atau owner vendor.
     */
    public function vendor(VendorDocument $document, Request $request)
    {
        $user = $request->user();

        $canView = $user && (
            $user->role === 'admin' ||
            ($user->role === 'vendor' && optional($user->vendor)->id === $document->vendor_id)
        );

        abort_unless($canView, 403);

        $relativePath = ltrim($document->file_path, '/');
        $localDisk = Storage::disk('local');

        if ($localDisk->exists($relativePath)) {
            return response()->file($localDisk->path($relativePath));
        }

        // Fallback legacy path for old uploads.
        $legacyPath = storage_path('app/' . $relativePath);
        if (file_exists($legacyPath)) {
            return response()->file($legacyPath);
        }

        abort(404, 'File dokumen tidak ditemukan.');
    }

    /**
     * Tampilkan file dokumen user untuk admin, owner user, atau vendor terkait booking.
     */
    public function user(UserDocument $document, Request $request)
    {
        $authUser = $request->user();

        abort_unless($authUser, 403);

        $isOwner = $authUser->role === 'user' && $authUser->id === $document->user_id;
        $isAdmin = $authUser->role === 'admin';
        $isVendorWithBooking = false;

        if ($authUser->role === 'vendor' && $authUser->vendor) {
            $isVendorWithBooking = Booking::query()
                ->where('user_id', $document->user_id)
                ->whereHas('vehicle', function ($query) use ($authUser) {
                    $query->where('vendor_id', $authUser->vendor->id);
                })
                ->exists();
        }

        abort_unless($isOwner || $isAdmin || $isVendorWithBooking, 403);

        $relativePath = ltrim($document->file_path, '/');
        $publicDisk = Storage::disk('public');

        if ($publicDisk->exists($relativePath)) {
            return response()->file($publicDisk->path($relativePath));
        }

        // Fallback when old data stored with "public/" prefix.
        if (str_starts_with($relativePath, 'public/')) {
            $trimmedPath = substr($relativePath, 7);
            if ($trimmedPath !== false && $publicDisk->exists($trimmedPath)) {
                return response()->file($publicDisk->path($trimmedPath));
            }
        }

        $legacyPath = storage_path('app/public/' . $relativePath);
        if (file_exists($legacyPath)) {
            return response()->file($legacyPath);
        }

        abort(404, 'File dokumen tidak ditemukan.');
    }

    /**
     * Tampilkan bukti pembayaran booking untuk user owner, vendor terkait, atau admin.
     */
    public function paymentProof(Payment $payment, Request $request)
    {
        $authUser = $request->user();

        abort_unless($authUser, 403);
        abort_unless($payment->proof_path, 404, 'Bukti pembayaran tidak ditemukan.');

        $payment->loadMissing('booking.vehicle');

        $isOwner = $authUser->role === 'user' && $payment->booking?->user_id === $authUser->id;
        $isAdmin = $authUser->role === 'admin';
        $isVendor = $authUser->role === 'vendor'
            && $authUser->vendor
            && $payment->booking
            && $payment->booking->vehicle
            && $payment->booking->vehicle->vendor_id === $authUser->vendor->id;

        abort_unless($isOwner || $isAdmin || $isVendor, 403);

        $relativePath = ltrim($payment->proof_path, '/');
        $publicDisk = Storage::disk('public');

        if ($publicDisk->exists($relativePath)) {
            return response()->file($publicDisk->path($relativePath));
        }

        abort(404, 'File bukti pembayaran tidak ditemukan.');
    }
}
