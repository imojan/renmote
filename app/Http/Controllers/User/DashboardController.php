<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * User dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Booking yang sedang aktif (pending atau confirmed)
        $activeBookings = $user->bookings()
            ->with('vehicle.vendor', 'payment')
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->get();

        // Riwayat booking (completed atau cancelled)
        $bookingHistory = $user->bookings()
            ->with('vehicle.vendor', 'payment')
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->get();

        return view('user.dashboard', compact('activeBookings', 'bookingHistory'));
    }
}
