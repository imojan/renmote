<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Daftar semua bookings
     */
    public function index()
    {
        $bookings = Booking::with('user', 'vehicle.vendor', 'payment')->latest()->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Detail booking
     */
    public function show(Booking $booking)
    {
        $booking->load('user', 'vehicle.vendor', 'payment');

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update status booking
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $this->bookingService->updateBookingStatus($booking, $request->status);

        return back()->with('success', 'Status booking berhasil diupdate.');
    }
}
