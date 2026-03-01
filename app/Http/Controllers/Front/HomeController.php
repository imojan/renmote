<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Halaman utama / landing page
     */
    public function index()
    {
        // Ambil beberapa kendaraan untuk ditampilkan di homepage
        $vehicles = Vehicle::with('vendor.district')
            ->where('status', 'available')
            ->latest()
            ->take(8)
            ->get();

        return view('front.home', compact('vehicles'));
    }
}
