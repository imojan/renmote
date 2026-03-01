<?php

namespace App\Http\Controllers\Vendor;

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
     * Daftar booking untuk kendaraan vendor
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;

        $bookings = Booking::with('user', 'vehicle', 'payment')
            ->whereHas('vehicle', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->latest()
            ->get();

        return view('vendor.bookings.index', compact('bookings'));
    }

    /**
     * Detail booking
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load('user', 'vehicle', 'payment');

        return view('vendor.bookings.show', compact('booking'));
    }

    /**
     * Konfirmasi booking
     */
    public function confirm(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status === 'pending') {
            $this->bookingService->updateBookingStatus($booking, 'confirmed');
            return back()->with('success', 'Booking berhasil dikonfirmasi.');
        }

        return back()->with('error', 'Booking tidak dapat dikonfirmasi.');
    }

    /**
     * Selesaikan booking
     */
    public function complete(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status === 'confirmed') {
            $this->bookingService->updateBookingStatus($booking, 'completed');
            return back()->with('success', 'Booking berhasil diselesaikan.');
        }

        return back()->with('error', 'Booking tidak dapat diselesaikan.');
    }

    /**
     * Pastikan booking untuk kendaraan milik vendor ini
     */
    private function authorizeBooking(Booking $booking)
    {
        $vendor = auth()->user()->vendor;
        if ($booking->vehicle->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }
    }
}
