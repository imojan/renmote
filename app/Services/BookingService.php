<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingService
{
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
        return DB::transaction(function () use ($user, $vehicle, $startDate, $endDate) {
            // Lock row kendaraan untuk mencegah booking paralel di kendaraan yang sama.
            $lockedVehicle = Vehicle::query()
                ->whereKey($vehicle->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedVehicle) {
                throw new Exception('Kendaraan tidak ditemukan.');
            }

            // Cek overlap di dalam transaksi yang sama agar anti race condition.
            $hasOverlappingBooking = Booking::query()
                ->where('vehicle_id', $lockedVehicle->id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                        ->where('end_date', '>=', $startDate);
                })
                ->lockForUpdate()
                ->exists();

            if ($hasOverlappingBooking) {
                throw new Exception('Kendaraan tidak tersedia untuk tanggal yang dipilih');
            }

            // Hitung total harga
            $totalPrice = $this->calculateTotalPrice($lockedVehicle, $startDate, $endDate);

            // Buat booking baru
            return Booking::create([
                'user_id' => $user->id,
                'vehicle_id' => $lockedVehicle->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
        });
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
