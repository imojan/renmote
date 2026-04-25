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
        $sortBy = $this->normalizeSortBy($request->query('sort_by'));
        $sortDir = $this->normalizeSortDir($request->query('sort_dir'));

        $bookingsQuery = Booking::query()
            ->select('bookings.*')
            ->with('user', 'vehicle', 'payment')
            ->leftJoin('users', 'users.id', '=', 'bookings.user_id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'bookings.vehicle_id')
            ->whereHas('vehicle', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->when($normalizedStatus, function ($query) use ($normalizedStatus) {
                $query->where('bookings.status', $normalizedStatus);
            });

        $this->applySorting($bookingsQuery, $sortBy, $sortDir);

        $bookings = $bookingsQuery->get();

        return view('vendor.bookings.index', compact('bookings', 'sortBy', 'sortDir'));
    }

    /**
     * Export booking vendor ke file Excel.
     */
    public function export(Request $request)
    {
        $vendor = auth()->user()->vendor;
        $status = $request->query('status');
        $sortBy = $this->normalizeSortBy($request->query('sort_by'));
        $sortDir = $this->normalizeSortDir($request->query('sort_dir'));

        $filename = 'booking-vendor-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new VendorBookingsExport($vendor->id, $status, $sortBy, $sortDir), $filename);
    }

    /**
     * Detail booking
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load('user.userDocuments', 'vehicle', 'payment', 'address.district');

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
     * Verifikasi bukti pembayaran sebagai vendor.
     */
    public function approvePaymentProof(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load('payment');

        if (!$booking->payment || !$booking->payment->proof_path) {
            return back()->with('error', 'Bukti pembayaran belum tersedia.');
        }

        if ($booking->payment->proof_status !== 'uploaded') {
            if ($booking->payment->proof_status === 'verified') {
                return back()->with('info', 'Bukti pembayaran sudah diverifikasi sebelumnya.');
            }

            if ($booking->payment->proof_status === 'rejected') {
                return back()->with('info', 'Bukti pembayaran ini sudah ditolak sebelumnya.');
            }

            return back()->with('error', 'Bukti pembayaran belum siap diverifikasi.');
        }

        $validated = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'proof_status' => 'verified',
            'proof_review_notes' => $validated['review_notes'] ?? null,
            'proof_reviewed_at' => now(),
            'proof_reviewer_role' => 'vendor',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diverifikasi.');
    }

    /**
     * Tolak bukti pembayaran sebagai vendor.
     */
    public function rejectPaymentProof(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load('payment');

        if (!$booking->payment || !$booking->payment->proof_path) {
            return back()->with('error', 'Bukti pembayaran belum tersedia.');
        }

        if ($booking->payment->proof_status !== 'uploaded') {
            if ($booking->payment->proof_status === 'rejected') {
                return back()->with('info', 'Bukti pembayaran ini sudah ditolak sebelumnya.');
            }

            if ($booking->payment->proof_status === 'verified') {
                return back()->with('error', 'Bukti pembayaran yang sudah diverifikasi tidak bisa ditolak.');
            }

            return back()->with('error', 'Bukti pembayaran belum siap ditolak.');
        }

        $validated = $request->validate([
            'review_notes' => ['required', 'string', 'max:500'],
        ]);

        $booking->payment->update([
            'status' => 'pending',
            'paid_at' => null,
            'proof_status' => 'rejected',
            'proof_review_notes' => $validated['review_notes'],
            'proof_reviewed_at' => now(),
            'proof_reviewer_role' => 'vendor',
        ]);

        return back()->with('success', 'Bukti pembayaran ditolak. User diminta upload ulang.');
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

    private function normalizeSortBy(?string $sortBy): string
    {
        $allowed = ['id', 'customer_name', 'vehicle_name', 'booking_date', 'total_paid'];

        return in_array($sortBy, $allowed, true) ? $sortBy : 'id';
    }

    private function normalizeSortDir(?string $sortDir): string
    {
        return in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'desc';
    }

    private function applySorting($query, string $sortBy, string $sortDir): void
    {
        $sortMap = [
            'id' => 'bookings.id',
            'customer_name' => 'users.name',
            'vehicle_name' => 'vehicles.name',
            'booking_date' => 'bookings.start_date',
            'total_paid' => 'bookings.total_price',
        ];

        $query->orderBy($sortMap[$sortBy], $sortDir)
            ->orderBy('bookings.id', 'desc');
    }
}
