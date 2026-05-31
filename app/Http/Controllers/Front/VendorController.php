<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    /**
     * Daftar vendor (paginated).
     */
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $vendorsQuery = Vendor::query()
            ->with(['district', 'vehicles' => fn ($q) => $q->where('status', 'available')])
            ->where('verified', true)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($inner) use ($keyword) {
                    $inner->where('store_name', 'like', "%{$keyword}%")
                        ->orWhereHas('district', fn ($d) => $d->where('name', 'like', "%{$keyword}%"));
                });
            })
            ->latest();

        $vendors = $vendorsQuery->paginate(9)->withQueryString();

        $wishlistedVendorIds = [];

        if (Auth::check() && Auth::user()->role === 'user') {
            $wishlistedVendorIds = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vendor::class)
                ->pluck('wishlistable_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return view('front.vendors.index', compact('vendors', 'keyword', 'wishlistedVendorIds'));
    }

    /**
     * Halaman detail vendor
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('district', 'user', 'vehicles');

        $isWishlistedVendor = false;
        $wishlistedVehicleIds = [];

        if (Auth::check() && Auth::user()->role === 'user') {
            $isWishlistedVendor = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vendor::class)
                ->where('wishlistable_id', $vendor->id)
                ->exists();

            $wishlistedVehicleIds = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vehicle::class)
                ->pluck('wishlistable_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }
        
        return view('front.vendors.show', compact('vendor', 'isWishlistedVendor', 'wishlistedVehicleIds'));
    }
}
