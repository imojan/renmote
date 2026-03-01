<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Vendor dashboard
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;

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

        // Revenue (sum dari payments yang sudah paid)
        $revenue = Payment::whereHas('booking.vehicle', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->where('status', 'paid')->sum('amount');

        return view('vendor.dashboard', compact(
            'vendor',
            'totalVehicles',
            'totalBookings',
            'pendingBookings',
            'revenue'
        ));
    }
}
