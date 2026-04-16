<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\UserDocument;
use App\Models\VendorDocument;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin dashboard
     */
    public function index()
    {
        // ── Stat counts ────────────────────────────
        $totalUsers    = User::where('role', 'user')->count();
        $totalVendors  = Vendor::count();
        $totalVehicles = Vehicle::count();
        $totalBookings = Booking::count();
        $pendingVendors   = Vendor::where('verified', false)->count();
        $pendingDocuments = VendorDocument::where('status', 'pending')->count()
            + UserDocument::where('status', 'pending')->count();

        // ── Recent users (last 5) ──────────────────
        $recentUsers = User::latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        // ── Pending vendor list (last 5) ───────────
        $pendingVendorList = Vendor::where('verified', false)
            ->with('user:id,name,email')
            ->latest()
            ->take(5)
            ->get();

        // ── Recent bookings (last 5) ───────────────
        $recentBookings = Booking::with(['user:id,name', 'vehicle:id,name'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVendors',
            'totalVehicles',
            'totalBookings',
            'pendingVendors',
            'pendingDocuments',
            'recentUsers',
            'pendingVendorList',
            'recentBookings'
        ));
    }
}
