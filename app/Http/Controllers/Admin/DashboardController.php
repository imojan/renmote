<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin dashboard
     */
    public function index()
    {
        // Total users (role = user)
        $totalUsers = User::where('role', 'user')->count();

        // Total vendors
        $totalVendors = Vendor::count();

        // Total vehicles
        $totalVehicles = Vehicle::count();

        // Total bookings
        $totalBookings = Booking::count();

        // Vendors pending verification
        $pendingVendors = Vendor::where('verified', false)->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVendors',
            'totalVehicles',
            'totalBookings',
            'pendingVendors'
        ));
    }
}
