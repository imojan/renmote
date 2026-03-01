<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

class PaymentService
{
    /**
     * Buat pembayaran DP (Down Payment) 30% dari total harga
     *
     * @param Booking $booking
     * @return Payment
     */
    public function createDpPayment(Booking $booking): Payment
    {
        // Hitung DP 30% dari total_price
        $dpAmount = $booking->total_price * 0.30;

        // Buat payment dengan type = dp, status = pending
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $dpAmount,
            'payment_type' => 'dp',
            'status' => 'pending',
        ]);

        return $payment;
    }

    /**
     * Buat pembayaran full (100% dari total harga)
     *
     * @param Booking $booking
     * @return Payment
     */
    public function createFullPayment(Booking $booking): Payment
    {
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'payment_type' => 'full',
            'status' => 'pending',
        ]);

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
