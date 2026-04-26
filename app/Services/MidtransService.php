<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Transaction;
use RuntimeException;

class MidtransService
{
    public function isConfigured(): bool
    {
        return (bool) config('services.midtrans.server_key')
            && (bool) config('services.midtrans.client_key');
    }

    public function getSnapScriptUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function createSnapTokenForBooking(Booking $booking): string
    {
        $this->configure();

        $booking->loadMissing('user', 'vehicle.vendor', 'address.district', 'payment');
        $payment = $booking->payment;

        if (!$payment) {
            throw new RuntimeException('Payment untuk booking ini belum tersedia.');
        }

        $orderId = (string) $payment->invoice_number;
        $grossAmount = (int) round((float) $payment->amount);
        $durationDays = max(1, Carbon::parse($booking->start_date)->diffInDays(Carbon::parse($booking->end_date)));
        $shippingAddress = $this->buildShippingAddress($booking);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'enabled_payments' => config('services.midtrans.enabled_payments', []),
            'item_details' => [
                [
                    'id' => 'booking-' . $booking->id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => 'DP Sewa ' . $booking->vehicle->name . ' (' . $durationDays . ' hari)',
                    'category' => 'vehicle-rental',
                ],
            ],
            'customer_details' => [
                'first_name' => (string) $booking->user->name,
                'email' => (string) $booking->user->email,
                'phone' => (string) ($booking->user->phone_number ?? ''),
                'billing_address' => $shippingAddress,
                'shipping_address' => $shippingAddress,
            ],
            'expiry' => [
                'start_time' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 30,
            ],
            'callbacks' => [
                'finish' => route('user.bookings.payment', $booking),
            ],
        ];

        return Snap::getSnapToken($params);
    }

    public function getTransactionStatus(string $orderId): array
    {
        $this->configure();

        $status = Transaction::status($orderId);

        return json_decode(json_encode($status, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
    }

    public function normalizeLanguage(string $locale): string
    {
        return str_starts_with(strtolower($locale), 'id') ? 'id' : 'en';
    }

    private function configure(): void
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Konfigurasi Midtrans belum lengkap.');
        }

        MidtransConfig::$serverKey = (string) config('services.midtrans.server_key');
        MidtransConfig::$isProduction = $this->isProduction();
        MidtransConfig::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        MidtransConfig::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    private function isProduction(): bool
    {
        return (bool) config('services.midtrans.is_production', false);
    }

    private function buildShippingAddress(Booking $booking): array
    {
        $snapshot = $booking->delivery_address_snapshot ?? [];

        if (!is_array($snapshot) || empty($snapshot)) {
            $address = $booking->address;

            return [
                'first_name' => (string) $booking->user->name,
                'address' => (string) ($address->street ?? '-'),
                'city' => (string) ($address->city ?? '-'),
                'postal_code' => (string) ($address->postal_code ?? '00000'),
                'country_code' => 'IDN',
            ];
        }

        return [
            'first_name' => (string) $booking->user->name,
            'address' => (string) Arr::get($snapshot, 'street', '-'),
            'city' => (string) Arr::get($snapshot, 'city', '-'),
            'postal_code' => (string) Arr::get($snapshot, 'postal_code', '00000'),
            'country_code' => 'IDN',
        ];
    }
}
