<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminBookingsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private readonly ?string $status = null,
        private readonly string $sortBy = 'id',
        private readonly string $sortDir = 'desc'
    ) {
    }

    public function collection(): Collection
    {
        $query = Booking::query()
            ->select('bookings.*')
            ->with(['user', 'vehicle.vendor'])
            ->leftJoin('users', 'users.id', '=', 'bookings.user_id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'bookings.vehicle_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vehicles.vendor_id');

        $normalizedStatus = $this->normalizeStatus($this->status);
        if ($normalizedStatus !== null) {
            $query->where('bookings.status', $normalizedStatus);
        }

        $this->applySorting($query, $this->normalizeSortBy($this->sortBy), $this->normalizeSortDir($this->sortDir));

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Booking',
            'Pelanggan',
            'Email Pelanggan',
            'Kendaraan',
            'Vendor',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Total Harga',
            'Dibuat Pada',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->user?->name ?? '-',
            $booking->user?->email ?? '-',
            $booking->vehicle?->name ?? '-',
            $booking->vehicle?->vendor?->store_name ?? '-',
            $booking->start_date,
            $booking->end_date,
            $booking->status === 'cancelled' ? 'declined' : $booking->status,
            (int) $booking->total_price,
            $booking->created_at?->format('Y-m-d H:i:s') ?? '-',
        ];
    }

    private function normalizeStatus(?string $status): ?string
    {
        if (!$status) {
            return null;
        }

        return $status === 'declined' ? 'cancelled' : $status;
    }

    private function normalizeSortBy(string $sortBy): string
    {
        $allowed = ['id', 'customer_name', 'vehicle_name', 'vendor_name', 'booking_date', 'total_paid'];

        return in_array($sortBy, $allowed, true) ? $sortBy : 'id';
    }

    private function normalizeSortDir(string $sortDir): string
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
