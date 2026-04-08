<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AdminBookingsExport;
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
     * Daftar semua bookings
     */
    public function index(Request $request)
    {
        $normalizedStatus = $this->normalizeStatus($request->query('status'));

        $bookings = Booking::with('user', 'vehicle.vendor', 'payment')
            ->when($normalizedStatus, function ($query) use ($normalizedStatus) {
                $query->where('status', $normalizedStatus);
            })
            ->latest()
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Export booking admin ke file Excel.
     */
    public function export(Request $request)
    {
        $status = $request->query('status');
        $filename = 'booking-admin-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AdminBookingsExport($status), $filename);
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
            'status' => 'required|in:pending,confirmed,cancelled,completed,declined',
        ]);

        $this->bookingService->updateBookingStatus(
            $booking,
            $this->normalizeStatus($request->status) ?? $request->status
        );

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

    private function normalizeStatus(?string $status): ?string
    {
        if (!$status) {
            return null;
        }

        return $status === 'declined' ? 'cancelled' : $status;
    }
}
