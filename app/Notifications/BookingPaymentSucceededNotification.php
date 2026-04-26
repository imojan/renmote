<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingPaymentSucceededNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public Payment $payment
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $actionUrl = match ($notifiable->role) {
            'admin' => route('admin.bookings.show', $this->booking),
            'vendor' => route('vendor.bookings.show', $this->booking),
            default => route('user.bookings.payment.proof', $this->booking),
        };

        return [
            'title' => 'Pembayaran Berhasil',
            'category' => 'payment',
            'booking_id' => $this->booking->id,
            'invoice_number' => $this->payment->invoice_number,
            'message' => "Pembayaran booking #{$this->booking->id} sudah diterima sistem.",
            'action_url' => $actionUrl,
            'action_label' => 'Lihat Detail',
        ];
    }
}
