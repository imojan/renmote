<?php

namespace App\Notifications;

use App\Models\UserDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserDocumentReviewed extends Notification
{
    use Queueable;

    public function __construct(
        public UserDocument $document
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = $this->document->type === 'ktp' ? 'KTP/KTM' : strtoupper($this->document->type);
        $statusText = $this->document->status === 'approved' ? 'DISETUJUI' : 'DITOLAK';
        
        $subject = "Verifikasi Dokumen {$typeLabel} - {$statusText}";
        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Halo {$notifiable->name},")
            ->line("Dokumen **{$typeLabel}** yang Anda unggah untuk verifikasi akun telah **{$statusText}**.");

        if ($this->document->notes) {
            $mail->line("Catatan/Alasan peninjau: \"{$this->document->notes}\"");
        }

        if ($this->document->status === 'rejected') {
            $mail->line('Silakan unggah kembali dokumen KTP/KTM atau SIM yang valid melalui pengaturan akun Anda.')
                 ->action('Perbarui Dokumen', route('user.account.index'));
        } else {
            $mail->action('Buka Pengaturan Akun', route('user.account.index'));
        }

        return $mail->line('Terima kasih atas kerja samanya.');
    }

    public function toArray(object $notifiable): array
    {
        $typeLabel = $this->document->type === 'ktp' ? 'KTP/KTM' : strtoupper($this->document->type);
        $statusText = $this->document->status === 'approved' ? 'disetujui' : 'ditolak';
        $message = "Dokumen {$typeLabel} Anda telah {$statusText}.";
        
        if ($this->document->notes) {
            $message .= " Catatan: " . $this->document->notes;
        }

        return [
            'title' => $this->document->status === 'approved' ? 'Verifikasi Dokumen Disetujui' : 'Verifikasi Dokumen Ditolak',
            'category' => 'document-verification',
            'document_id' => $this->document->id,
            'document_type' => $this->document->type,
            'status' => $this->document->status,
            'message' => $message,
            'action_url' => route('user.account.index'),
            'action_label' => 'Buka Pengaturan Akun',
        ];
    }
}
