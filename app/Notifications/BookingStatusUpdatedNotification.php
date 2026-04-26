<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $status,
        public ?string $note = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Ditolak',
            'completed' => 'Selesai',
            'pending' => 'Menunggu',
        ];

        $statusLabel = $statusLabels[$this->status] ?? ucfirst($this->status);
        $message = "Status booking #{$this->booking->id} diperbarui menjadi {$statusLabel}.";

        if ($this->note) {
            $message .= ' ' . $this->note;
        }

        return [
            'title' => 'Status Booking Diperbarui',
            'category' => 'booking',
            'booking_id' => $this->booking->id,
            'status' => $this->status,
            'message' => $message,
            'action_url' => route('user.bookings.show', $this->booking),
            'action_label' => 'Lihat Booking',
        ];
    }
}
