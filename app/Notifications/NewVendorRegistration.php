<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVendorRegistration extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Vendor $vendor
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
            ->subject('Pendaftaran Vendor Baru Menunggu Verifikasi')
            ->greeting('Halo Admin Renmote!')
            ->line("Vendor baru dengan nama toko **{$this->vendor->store_name}** telah mendaftar di platform.")
            ->line("Silakan tinjau dokumen verifikasi dan informasi toko untuk menyetujui atau menolak pendaftaran ini.")
            ->action('Tinjau Pendaftaran Vendor', route('admin.vendors.show', $this->vendor))
            ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'      => 'Vendor Baru Menunggu Verifikasi',
            'vendor_id'  => $this->vendor->id,
            'store_name' => $this->vendor->store_name,
            'category'   => 'vendor-registration',
            'message'    => "Vendor baru '{$this->vendor->store_name}' mendaftar dan menunggu verifikasi.",
            'action_url' => route('admin.vendors.show', $this->vendor),
            'action_label' => 'Tinjau Data Vendor',
        ];
    }
}
