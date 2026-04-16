<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Vendor dashboard
     */
    public function index()
    {
        $vendor = auth()->user()->vendor()->with(['documents' => fn ($query) => $query->latest()])->first();

        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'Anda belum terdaftar sebagai vendor.');
        }

        // Total kendaraan
        $totalVehicles = $vendor->vehicles()->count();

        // Total booking
        $totalBookings = $vendor->vehicles()
            ->withCount('bookings')
            ->get()
            ->sum('bookings_count');

        // Pending bookings
        $pendingBookings = $vendor->vehicles()
            ->with(['bookings' => function ($q) {
                $q->where('status', 'pending');
            }])
            ->get()
            ->pluck('bookings')
            ->flatten()
            ->count();

        // Revenue dari booking yang sudah confirmed/completed.
        $revenue = Booking::whereHas('vehicle', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_price');

        return view('vendor.dashboard', compact(
            'vendor',
            'totalVehicles',
            'totalBookings',
            'pendingBookings',
            'revenue'
        ));
    }
}
