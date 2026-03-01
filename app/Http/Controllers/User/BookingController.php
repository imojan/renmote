<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreBookingRequest;
use App\Models\Vehicle;
use App\Services\BookingService;
use App\Services\PaymentService;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;

    public function __construct(BookingService $bookingService, PaymentService $paymentService)
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
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

        return view('user.bookings.index', compact('bookings'));
    }

    /**
     * Form untuk membuat booking baru
     */
    public function create(Vehicle $vehicle)
    {
        return view('user.bookings.create', compact('vehicle'));
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

        return view('user.bookings.show', compact('booking'));
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
