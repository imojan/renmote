<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

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
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->approved ? 'DISETUJUI' : 'DITOLAK';
        $subject = $this->approved ? "Bukti Pembayaran Disetujui - Booking #{$this->booking->id}" : "Bukti Pembayaran Ditolak - Booking #{$this->booking->id}";
        
        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Halo {$notifiable->name},")
            ->line("Bukti pembayaran yang Anda unggah untuk booking **#{$this->booking->id}** (Invoice: **{$this->payment->invoice_number}**) telah **{$statusText}**.");

        if ($this->notes) {
            $mail->line("Catatan peninjau: \"{$this->notes}\"");
        }

        if (!$this->approved) {
            $mail->line('Silakan unggah kembali bukti pembayaran yang sah melalui halaman detail pesanan Anda.')
                 ->action('Unggah Bukti Ulang', route('user.bookings.payment.proof', $this->booking));
        } else {
            $mail->action('Lihat Pesanan', route('user.bookings.show', $this->booking));

            // Attach PDF invoice for the user when approved
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

        return $mail->line('Terima kasih.');
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
