<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Exception;

class BookingService
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Hitung total harga sewa berdasarkan jumlah hari
     *
     * @param Vehicle $vehicle
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function calculateTotalPrice(Vehicle $vehicle, string $startDate, string $endDate): float
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        // Hitung jumlah hari (minimal 1 hari)
        $days = $start->diffInDays($end) + 1;
        
        return $vehicle->price_per_day * $days;
    }

    /**
     * Buat booking baru
     *
     * @param User $user
     * @param Vehicle $vehicle
     * @param string $startDate
     * @param string $endDate
     * @return Booking
     * @throws Exception
     */
    public function createBooking(User $user, Vehicle $vehicle, string $startDate, string $endDate): Booking
    {
        // Cek ketersediaan kendaraan menggunakan AvailabilityService
        if (!$this->availabilityService->checkAvailability($vehicle->id, $startDate, $endDate)) {
            throw new Exception('Kendaraan tidak tersedia untuk tanggal yang dipilih');
        }

        // Hitung total harga
        $totalPrice = $this->calculateTotalPrice($vehicle, $startDate, $endDate);

        // Buat booking baru
        $booking = Booking::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return $booking;
    }

    /**
     * Update status booking
     *
     * @param Booking $booking
     * @param string $status (pending, confirmed, cancelled, completed)
     * @return Booking
     */
    public function updateBookingStatus(Booking $booking, string $status): Booking
    {
        $booking->update([
            'status' => $status,
        ]);

        return $booking;
    }
}
