<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Halaman wishlist user (kendaraan + vendor).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $vehicleWishlists = $user->wishlists()
            ->where('wishlistable_type', Vehicle::class)
            ->with('wishlistable.vendor.district')
            ->latest()
            ->get();

        $vendorWishlists = $user->wishlists()
            ->where('wishlistable_type', Vendor::class)
            ->with('wishlistable.district')
            ->latest()
            ->get();

        $wishlistVehicles = $vehicleWishlists
            ->map(fn (Wishlist $wishlist) => $wishlist->wishlistable)
            ->filter();

        $wishlistVendors = $vendorWishlists
            ->map(fn (Wishlist $wishlist) => $wishlist->wishlistable)
            ->filter();

        return view('front.bookings.wishlist', compact('wishlistVehicles', 'wishlistVendors'));
    }

    /**
     * Toggle wishlist untuk kendaraan.
     */
    public function toggleVehicle(Request $request, Vehicle $vehicle)
    {
        $this->ensureUserRole($request);

        $deleted = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('wishlistable_type', Vehicle::class)
            ->where('wishlistable_id', $vehicle->id)
            ->delete();

        if ($deleted === 0) {
            Wishlist::create([
                'user_id' => $request->user()->id,
                'wishlistable_type' => Vehicle::class,
                'wishlistable_id' => $vehicle->id,
            ]);
        }

        $message = $deleted ? 'Kendaraan dihapus dari wishlist.' : 'Kendaraan ditambahkan ke wishlist.';

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'wishlisted' => $deleted === 0,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle wishlist untuk vendor.
     */
    public function toggleVendor(Request $request, Vendor $vendor)
    {
        $this->ensureUserRole($request);

        $deleted = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('wishlistable_type', Vendor::class)
            ->where('wishlistable_id', $vendor->id)
            ->delete();

        if ($deleted === 0) {
            Wishlist::create([
                'user_id' => $request->user()->id,
                'wishlistable_type' => Vendor::class,
                'wishlistable_id' => $vendor->id,
            ]);
        }

        $message = $deleted ? 'Vendor dihapus dari wishlist.' : 'Vendor ditambahkan ke wishlist.';

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'wishlisted' => $deleted === 0,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    private function ensureUserRole(Request $request): void
    {
        if (!$request->user() || $request->user()->role !== 'user') {
            abort(403);
        }
    }
}
