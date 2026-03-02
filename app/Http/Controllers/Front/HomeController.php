<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\District;
use App\Models\Vendor;
use Illuminate\Http\Request;

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

        return view('front.home', compact('districts', 'popularVehicles', 'vendors'));
    }
}
