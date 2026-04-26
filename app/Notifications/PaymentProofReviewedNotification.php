<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentProofReviewedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public Payment $payment,
        public bool $approved,
        public ?string $notes = null,
        public ?string $reviewerRole = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->approved ? 'disetujui' : 'ditolak';
        $reviewer = $this->reviewerRole ? strtoupper($this->reviewerRole) : 'ADMIN/VENDOR';
        $message = "Bukti pembayaran booking #{$this->booking->id} {$statusText} oleh {$reviewer}.";

        if ($this->notes) {
            $message .= ' Catatan: ' . $this->notes;
        }

        return [
            'title' => $this->approved ? 'Bukti Pembayaran Disetujui' : 'Bukti Pembayaran Ditolak',
            'category' => 'payment-proof',
            'booking_id' => $this->booking->id,
            'invoice_number' => $this->payment->invoice_number,
            'approved' => $this->approved,
            'message' => $message,
            'action_url' => route('user.bookings.show', $this->booking),
            'action_label' => 'Buka Detail Booking',
        ];
    }
}
