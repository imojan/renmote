<?php

namespace App\Notifications;

use App\Models\VendorDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorDocumentReviewed extends Notification
{
    use Queueable;

    public function __construct(
        public VendorDocument $document
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $types = [
            'ktp'    => 'KTP Pemilik',
            'permit' => 'Surat Izin Usaha',
            'photo'  => 'Foto Toko / Lokasi',
        ];
        
        $typeLabel = $types[$this->document->type] ?? strtoupper($this->document->type);
        $statusText = $this->document->status === 'approved' ? 'DISETUJUI' : 'DITOLAK';
        
        $subject = "Verifikasi Dokumen Toko {$typeLabel} - {$statusText}";
        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Halo {$notifiable->name},")
            ->line("Dokumen toko **{$typeLabel}** untuk usaha Anda telah **{$statusText}** oleh admin.");

        if ($this->document->notes) {
            $mail->line("Catatan/Alasan peninjau: \"{$this->document->notes}\"");
        }

        if ($this->document->status === 'rejected') {
            $mail->line('Silakan unggah kembali dokumen toko yang valid melalui pengaturan profil vendor Anda.')
                 ->action('Perbarui Dokumen Toko', route('vendor.profile.edit'));
        } else {
            $mail->action('Buka Pengaturan Vendor', route('vendor.profile.edit'));
        }

        return $mail->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        $types = [
            'ktp'    => 'KTP Pemilik',
            'permit' => 'Surat Izin Usaha',
            'photo'  => 'Foto Toko / Lokasi',
        ];
        
        $typeLabel = $types[$this->document->type] ?? strtoupper($this->document->type);
        $statusText = $this->document->status === 'approved' ? 'disetujui' : 'ditolak';
        $message = "Dokumen toko {$typeLabel} Anda telah {$statusText} oleh admin.";
        
        if ($this->document->notes) {
            $message .= " Catatan: " . $this->document->notes;
        }

        return [
            'title' => $this->document->status === 'approved' ? 'Dokumen Toko Disetujui' : 'Dokumen Toko Ditolak',
            'category' => 'vendor-registration',
            'document_id' => $this->document->id,
            'document_type' => $this->document->type,
            'status' => $this->document->status,
            'message' => $message,
            'action_url' => route('vendor.profile.edit'),
            'action_label' => 'Buka Pengaturan Vendor',
        ];
    }
}
