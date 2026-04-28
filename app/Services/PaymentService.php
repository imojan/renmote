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
            ?? $this->generateInvoiceNumber($booking->id);

        $expiresAt = $attributes['expires_at'] ?? Carbon::now()->addMinutes(30);

        // Buat payment dengan type = dp, status = pending
        $payment = Payment::create(array_merge([
            'booking_id' => $booking->id,
            'amount' => $dpAmount,
            'payment_type' => 'dp',
            'status' => 'pending',
            'gateway_status' => 'pending',
            'provider' => 'midtrans_sandbox',
            'payment_method' => 'qris',
            'invoice_number' => $invoiceNumber,
            'snap_token' => null,
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
            'gateway_status' => 'pending',
            'provider' => 'midtrans_sandbox',
            'payment_method' => 'qris',
            'invoice_number' => $this->generateInvoiceNumber($booking->id),
            'snap_token' => null,
        ], $attributes));

        return $payment;
    }

    /**
     * Buat nomor invoice yang unik untuk Midtrans order_id.
     */
    public function generateInvoiceNumber(int $bookingId): string
    {
        return 'INV-RNM-' . now()->format('YmdHis')
            . '-' . str_pad((string) $bookingId, 6, '0', STR_PAD_LEFT)
            . '-' . str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Regenerasi invoice saat transaksi lama gagal/expired.
     */
    public function regenerateInvoice(Payment $payment): Payment
    {
        $payment->update([
            'invoice_number' => $this->generateInvoiceNumber($payment->booking_id),
            'snap_token' => null,
            'status' => 'pending',
            'gateway_status' => 'pending',
            'gateway_transaction_id' => null,
            'gateway_payment_type' => null,
            'gateway_payload' => null,
            'gateway_last_synced_at' => null,
            'paid_at' => null,
            'expires_at' => now()->addMinutes(30),
        ]);

        return $payment->refresh();
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
            'gateway_status' => 'paid',
            'paid_at' => now(),
        ]);

        return $payment;
    }

    /**
     * Sinkronkan status transaksi Midtrans ke payment internal.
     */
    public function syncMidtransTransaction(Payment $payment, array $payload): Payment
    {
        $transactionStatus = strtolower((string) ($payload['transaction_status'] ?? 'pending'));
        $fraudStatus = strtolower((string) ($payload['fraud_status'] ?? ''));
        $paymentType = strtolower((string) ($payload['payment_type'] ?? ''));

        $gatewayStatus = $payment->gateway_status ?? 'pending';
        $localStatus = $payment->status ?? 'pending';
        $paidAt = $payment->paid_at;

        // Status paid bersifat final, jangan diturunkan lagi oleh update webhook/polling yang terlambat.
        if ($localStatus !== 'paid') {
            $gatewayStatus = 'pending';
            $localStatus = 'pending';
            $paidAt = null;

            if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus !== 'challenge')) {
                $gatewayStatus = 'paid';
                $localStatus = 'paid';
                $paidAt = now();
            } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny', 'failure'], true)) {
                $gatewayStatus = 'failed';
            }
        }

        $payment->update([
            'provider' => 'midtrans_sandbox',
            'status' => $localStatus,
            'gateway_status' => $gatewayStatus,
            'gateway_transaction_id' => $payload['transaction_id'] ?? null,
            'gateway_payment_type' => $paymentType !== '' ? $paymentType : null,
            'gateway_payload' => $payload,
            'gateway_last_synced_at' => now(),
            'paid_at' => $paidAt,
        ]);

        return $payment->refresh();
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
