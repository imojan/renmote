<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorApproved extends Notification
{
    use Queueable;

    public function __construct(
        public Vendor $vendor
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Selamat! Vendor Anda Telah Diverifikasi')
            ->greeting("Halo {$notifiable->name}!")
            ->line("Toko **{$this->vendor->store_name}** telah diverifikasi oleh admin.")
            ->line('Anda sekarang bisa mulai menambahkan kendaraan dan menerima pesanan.')
            ->action('Buka Dashboard Vendor', url('/vendor/dashboard'))
            ->line('Terima kasih telah bergabung dengan Renmote!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'vendor_id'  => $this->vendor->id,
            'store_name' => $this->vendor->store_name,
            'message'    => "Selamat! Toko '{$this->vendor->store_name}' telah diverifikasi.",
        ];
    }
}
