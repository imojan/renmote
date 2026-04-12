<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;
    protected AvailabilityService $availabilityService;

    public function __construct(
        BookingService $bookingService,
        PaymentService $paymentService,
        AvailabilityService $availabilityService
    )
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->availabilityService = $availabilityService;
    }

    /**
     * Daftar semua booking user
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('vehicle.vendor', 'payment')
            ->latest()
            ->get();

        return view('front.bookings.index', compact('bookings'));
    }

    /**
     * Form untuk membuat booking baru
     */
    public function create(Vehicle $vehicle)
    {
        return view('front.bookings.create', compact('vehicle'));
    }

    /**
     * Cek ketersediaan kendaraan secara real-time untuk ditampilkan di form booking.
     */
    public function checkAvailability(Request $request, Vehicle $vehicle): JsonResponse
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate && !$endDate) {
            return response()->json([
                'available' => true,
                'message' => 'Pilih tanggal sewa untuk cek ketersediaan.',
                'overlaps' => [],
            ]);
        }

        if ($startDate && !$endDate) {
            $endDate = $startDate;
        }

        if (!$startDate && $endDate) {
            $startDate = $endDate;
        }

        $isAvailable = $this->availabilityService->checkAvailability(
            $vehicle->id,
            $startDate,
            $endDate
        );

        $overlappingRanges = [];

        if (!$isAvailable) {
            $overlappingRanges = Booking::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('status', '!=', 'cancelled')
                ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate)
                ->orderBy('start_date')
                ->get(['start_date', 'end_date'])
                ->map(function (Booking $booking) {
                    return [
                        'start_date' => $booking->start_date,
                        'end_date' => $booking->end_date,
                    ];
                })
                ->toArray();
        }

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable
                ? 'Tanggal tersedia untuk dibooking.'
                : 'Tanggal yang dipilih sudah digunakan booking lain.',
            'overlaps' => $overlappingRanges,
        ]);
    }

    /**
     * Simpan booking baru
     */
    public function store(StoreBookingRequest $request, Vehicle $vehicle)
    {
        try {
            // Buat booking
            $booking = $this->bookingService->createBooking(
                auth()->user(),
                $vehicle,
                $request->validated()['start_date'],
                $request->validated()['end_date']
            );

            // Buat DP payment (30%)
            $this->paymentService->createDpPayment($booking);

            return redirect()->route('user.bookings.index')
                ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran DP.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Detail booking
     */
    public function show($id)
    {
        $booking = auth()->user()->bookings()
            ->with('vehicle.vendor', 'payment')
            ->findOrFail($id);

        return view('front.bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $booking = auth()->user()->bookings()->findOrFail($id);
        
        if ($booking->status === 'pending') {
            $this->bookingService->updateBookingStatus($booking, 'cancelled');
            return back()->with('success', 'Booking berhasil dibatalkan.');
        }

        return back()->with('error', 'Booking tidak dapat dibatalkan.');
    }
}
