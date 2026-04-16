<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Vendor;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Halaman detail kendaraan
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load('vendor.district', 'vendor.user');

        $isWishlistedVehicle = false;
        $isWishlistedVendor = false;

        if (Auth::check() && Auth::user()->role === 'user') {
            $isWishlistedVehicle = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vehicle::class)
                ->where('wishlistable_id', $vehicle->id)
                ->exists();

            $isWishlistedVendor = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vendor::class)
                ->where('wishlistable_id', $vehicle->vendor_id)
                ->exists();
        }
        
        return view('front.vehicles.show', compact('vehicle', 'isWishlistedVehicle', 'isWishlistedVendor'));
    }
}
