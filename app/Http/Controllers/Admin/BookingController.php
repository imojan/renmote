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
        $sortBy = $this->normalizeSortBy($request->query('sort_by'));
        $sortDir = $this->normalizeSortDir($request->query('sort_dir'));

        $bookingsQuery = Booking::query()
            ->select('bookings.*')
            ->with('user', 'vehicle.vendor', 'payment')
            ->leftJoin('users', 'users.id', '=', 'bookings.user_id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'bookings.vehicle_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vehicles.vendor_id')
            ->when($normalizedStatus, function ($query) use ($normalizedStatus) {
                $query->where('bookings.status', $normalizedStatus);
            });

        $this->applySorting($bookingsQuery, $sortBy, $sortDir);

        $bookings = $bookingsQuery->get();

        return view('admin.bookings.index', compact('bookings', 'sortBy', 'sortDir'));
    }

    /**
     * Export booking admin ke file Excel.
     */
    public function export(Request $request)
    {
        $status = $request->query('status');
        $sortBy = $this->normalizeSortBy($request->query('sort_by'));
        $sortDir = $this->normalizeSortDir($request->query('sort_dir'));
        $filename = 'booking-admin-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AdminBookingsExport($status, $sortBy, $sortDir), $filename);
    }

    /**
     * Detail booking
     */
    public function show(Booking $booking)
    {
        $booking->load('user.userDocuments', 'vehicle.vendor', 'payment', 'address.district');

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

    /**
     * Verifikasi bukti pembayaran sebagai admin.
     */
    public function approvePaymentProof(Request $request, Booking $booking)
    {
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
            'proof_reviewer_role' => 'admin',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diverifikasi.');
    }

    /**
     * Tolak bukti pembayaran sebagai admin.
     */
    public function rejectPaymentProof(Request $request, Booking $booking)
    {
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
            'proof_reviewer_role' => 'admin',
        ]);

        return back()->with('success', 'Bukti pembayaran ditolak. User diminta upload ulang.');
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
        $allowed = ['id', 'customer_name', 'vehicle_name', 'vendor_name', 'booking_date', 'total_paid'];

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
            'vendor_name' => 'vendors.store_name',
            'booking_date' => 'bookings.start_date',
            'total_paid' => 'bookings.total_price',
        ];

        $query->orderBy($sortMap[$sortBy], $sortDir)
            ->orderBy('bookings.id', 'desc');
    }
}
