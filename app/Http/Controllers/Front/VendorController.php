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
