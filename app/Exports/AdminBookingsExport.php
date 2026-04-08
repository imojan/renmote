<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminBookingsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly ?string $status = null)
    {
    }

    public function collection(): Collection
    {
        $query = Booking::with(['user', 'vehicle.vendor'])
            ->latest();

        $normalizedStatus = $this->normalizeStatus($this->status);
        if ($normalizedStatus !== null) {
            $query->where('status', $normalizedStatus);
        }

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
}
