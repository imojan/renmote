<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Exports\VendorBookingsExport;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $normalizedStatus = $this->normalizeStatus($request->query('status'));

        $bookings = Booking::with('user', 'vehicle', 'payment')
            ->whereHas('vehicle', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->when($normalizedStatus, function ($query) use ($normalizedStatus) {
                $query->where('status', $normalizedStatus);
            })
            ->latest()
            ->get();

        return view('vendor.bookings.index', compact('bookings'));
    }

    /**
     * Export booking vendor ke file Excel.
     */
    public function export(Request $request)
    {
        $vendor = auth()->user()->vendor;
        $status = $request->query('status');

        $filename = 'booking-vendor-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new VendorBookingsExport($vendor->id, $status), $filename);
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
     * Tolak booking
     */
    public function reject(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if (in_array($booking->status, ['pending', 'confirmed'], true)) {
            $this->bookingService->updateBookingStatus($booking, 'cancelled');
            return back()->with('success', 'Booking berhasil ditolak.');
        }

        return back()->with('error', 'Booking tidak dapat ditolak.');
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
        if (!$booking->vehicle || $booking->vehicle->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }
    }

    private function normalizeStatus(?string $status): ?string
    {
        if (!$status) {
            return null;
        }

        return $status === 'declined' ? 'cancelled' : $status;
    }
}
