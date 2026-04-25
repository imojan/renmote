<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class PaymentService
{
    /**
     * Buat pembayaran DP (Down Payment) 30% dari total harga
     *
     * @param Booking $booking
     * @return Payment
     */
    public function createDpPayment(Booking $booking, array $attributes = []): Payment
    {
        // Hitung DP 30% dari total_price
        $dpAmount = $booking->total_price * 0.30;

        $invoiceNumber = $attributes['invoice_number']
            ?? ('INV-RNM-' . now()->format('Ymd') . '-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT));

        $expiresAt = $attributes['expires_at'] ?? Carbon::now()->addMinutes(30);

        // Buat payment dengan type = dp, status = pending
        $payment = Payment::create(array_merge([
            'booking_id' => $booking->id,
            'amount' => $dpAmount,
            'payment_type' => 'dp',
            'status' => 'pending',
            'provider' => 'manual_qris',
            'payment_method' => 'qris',
            'invoice_number' => $invoiceNumber,
            'expires_at' => $expiresAt,
            'proof_status' => 'not_uploaded',
        ], $attributes));

        return $payment;
    }

    /**
     * Buat pembayaran full (100% dari total harga)
     *
     * @param Booking $booking
     * @return Payment
     */
    public function createFullPayment(Booking $booking, array $attributes = []): Payment
    {
        $payment = Payment::create(array_merge([
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'payment_type' => 'full',
            'status' => 'pending',
            'provider' => 'manual_qris',
            'payment_method' => 'qris',
        ], $attributes));

        return $payment;
    }

    /**
     * Update status pembayaran menjadi paid
     *
     * @param Payment $payment
     * @return Payment
     */
    public function markAsPaid(Payment $payment): Payment
    {
        $payment->update([
            'status' => 'paid',
        ]);

        return $payment;
    }

    /**
     * Hitung sisa pembayaran (untuk DP)
     *
     * @param Booking $booking
     * @return float
     */
    public function getRemainingAmount(Booking $booking): float
    {
        $paidAmount = $booking->payment ? $booking->payment->amount : 0;
        return $booking->total_price - $paidAmount;
    }
}
