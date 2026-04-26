<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorRejected extends Notification
{
    use Queueable;

    public function __construct(
        public Vendor $vendor,
        public string $reason = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Update Status Pendaftaran Vendor')
            ->greeting("Halo {$notifiable->name},")
            ->line("Mohon maaf, pendaftaran toko **{$this->vendor->store_name}** belum dapat disetujui.");

        if ($this->reason) {
            $mail->line("Alasan: {$this->reason}");
        }

        return $mail
            ->line('Silakan perbaiki data dan coba daftarkan kembali.')
            ->action('Hubungi Support', url('/'))
            ->line('Terima kasih atas pengertian Anda.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'      => 'Pendaftaran Vendor Ditolak',
            'vendor_id'  => $this->vendor->id,
            'store_name' => $this->vendor->store_name,
            'reason'     => $this->reason,
            'category'   => 'vendor-registration',
            'message'    => "Pendaftaran toko '{$this->vendor->store_name}' ditolak.",
            'action_url' => route('vendor.register'),
            'action_label' => 'Perbarui Data Vendor',
        ];
    }
}
