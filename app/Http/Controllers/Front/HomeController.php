<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Vehicle;
use App\Models\District;
use App\Models\Vendor;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Halaman utama / landing page
     */
    public function index()
    {
        // Districts untuk filter pencarian
        $districts = District::all();

        // Motor populer (vehicle dengan booking terbanyak / terbaru)
        $popularVehicles = Vehicle::with('vendor.district')
            ->where('status', 'available')
            ->latest()
            ->take(5)
            ->get();

        // Vendor andalan (verified, dengan jumlah kendaraan)
        $vendors = Vendor::with(['district', 'vehicles'])
            ->where('verified', true)
            ->latest()
            ->take(3)
            ->get();

        $articles = Article::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $wishlistedVehicleIds = [];
        $wishlistedVendorIds = [];

        if (Auth::check() && Auth::user()->role === 'user') {
            $wishlistedVehicleIds = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vehicle::class)
                ->pluck('wishlistable_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $wishlistedVendorIds = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('wishlistable_type', Vendor::class)
                ->pluck('wishlistable_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return view('front.home', compact(
            'districts',
            'popularVehicles',
            'vendors',
            'articles',
            'wishlistedVehicleIds',
            'wishlistedVendorIds'
        ));
    }
}
