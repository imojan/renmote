<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingPaymentFailedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public Payment $payment,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = "Mohon maaf, pembayaran Anda untuk booking **#{$this->booking->id}** (Invoice: **{$this->payment->invoice_number}**) gagal atau telah kadaluarsa.";
        if ($this->reason) {
            $message .= " Alasan: **{$this->reason}**";
        }

        return (new MailMessage)
            ->subject("Pembayaran Gagal - Invoice #{$this->payment->invoice_number}")
            ->greeting("Halo {$notifiable->name},")
            ->line($message)
            ->line('Silakan lakukan pembayaran ulang atau hubungi tim bantuan kami jika Anda mengalami kendala.')
            ->action('Ajukan Ulang Pembayaran', route('user.bookings.payment', $this->booking))
            ->line('Terima kasih.');
    }


    public function toArray(object $notifiable): array
    {
        $baseMessage = "Pembayaran untuk booking #{$this->booking->id} gagal atau kadaluarsa.";
        $message = $this->reason ? $baseMessage . ' ' . $this->reason : $baseMessage;

        return [
            'title' => 'Pembayaran Gagal',
            'category' => 'payment',
            'booking_id' => $this->booking->id,
            'invoice_number' => $this->payment->invoice_number,
            'message' => $message,
            'action_url' => route('user.bookings.payment', $this->booking),
            'action_label' => 'Ajukan Ulang Pembayaran',
        ];
    }
}
