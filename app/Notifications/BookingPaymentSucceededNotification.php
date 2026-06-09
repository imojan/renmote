<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingPaymentSucceededNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public Payment $payment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Pembayaran Berhasil - Invoice #{$this->payment->invoice_number}")
            ->greeting("Halo {$notifiable->name}!");

        if ($notifiable->role === 'admin') {
            $mail->line("Pembayaran untuk booking **#{$this->booking->id}** dengan nomor invoice **{$this->payment->invoice_number}** telah berhasil diterima oleh sistem.")
                 ->action('Lihat Detail Booking', route('admin.bookings.show', $this->booking));
        } elseif ($notifiable->role === 'vendor') {
            $mail->line("Pembayaran untuk booking **#{$this->booking->id}** oleh pelanggan Anda telah berhasil dikonfirmasi.")
                 ->line("Silakan bersiap untuk menyiapkan kendaraan sesuai jadwal sewa.")
                 ->action('Lihat Detail Booking', route('vendor.bookings.show', $this->booking));
        } else {
            $mail->line("Terima kasih! Pembayaran Anda untuk booking **#{$this->booking->id}** (Invoice: **{$this->payment->invoice_number}**) telah berhasil dikonfirmasi oleh sistem.")
                 ->line("Detail Transaksi:")
                 ->line("- Kendaraan: **{$this->booking->vehicle->name}**")
                 ->line("- Jumlah Hari: **{$this->booking->duration_days} hari**")
                 ->line("- Total Bayar: **Rp " . number_format($this->payment->amount, 0, ',', '.') . "**")
                 ->action('Lihat Detail Booking', route('user.bookings.show', $this->booking));

            // Attach PDF invoice for the user
            $this->booking->loadMissing(['user', 'vehicle.vendor', 'payment', 'address']);
            $pdf = Pdf::loadView('front.bookings.invoice-pdf', [
                'booking' => $this->booking,
            ])->setPaper('a4', 'portrait');

            $mail->attachData(
                $pdf->output(),
                "invoice-{$this->payment->invoice_number}.pdf",
                ['mime' => 'application/pdf']
            );
        }

        return $mail->line('Terima kasih telah mempercayai Renmote.');
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
