<?php

namespace App\Services;

use App\Models\Booking;

class AvailabilityService
{
    /**
     * Cek ketersediaan kendaraan untuk rentang tanggal tertentu
     *
     * Logic: Return false jika ada booking yang overlap dengan tanggal yang diminta
     * Overlap terjadi jika: existing.start_date <= requested_end_date AND existing.end_date >= requested_start_date
     *
     * @param int $vehicleId
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public function checkAvailability(int $vehicleId, string $startDate, string $endDate): bool
    {
        // Cari booking yang overlap dengan tanggal yang diminta
        // Hanya cek booking yang statusnya bukan cancelled
        $overlappingBooking = Booking::where('vehicle_id', $vehicleId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
            })
            ->exists();

        // Return true jika TIDAK ADA booking yang overlap (tersedia)
        // Return false jika ADA booking yang overlap (tidak tersedia)
        return !$overlappingBooking;
    }
}
