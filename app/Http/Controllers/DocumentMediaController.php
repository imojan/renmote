<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\UserDocument;
use App\Models\VendorDocument;
use Illuminate\Http\Request;

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

        $path = storage_path('app/' . ltrim($document->file_path, '/'));
        abort_unless(file_exists($path), 404, 'File dokumen tidak ditemukan.');

        return response()->file($path);
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

        $path = storage_path('app/public/' . ltrim($document->file_path, '/'));
        abort_unless(file_exists($path), 404, 'File dokumen tidak ditemukan.');

        return response()->file($path);
    }
}
