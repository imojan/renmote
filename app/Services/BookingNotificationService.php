<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\BookingPaymentFailedNotification;
use App\Notifications\BookingPaymentSucceededNotification;
use App\Notifications\BookingStatusUpdatedNotification;
use App\Notifications\PaymentProofReviewedNotification;

class BookingNotificationService
{
    public function notifyPaymentSuccess(Booking $booking, Payment $payment): void
    {
        $booking->loadMissing('user', 'vehicle.vendor.user');

        $booking->user?->notify(new BookingPaymentSucceededNotification($booking, $payment));

        $vendorOwner = $booking->vehicle?->vendor?->user;
        if ($vendorOwner) {
            $vendorOwner->notify(new BookingPaymentSucceededNotification($booking, $payment));
        }

        User::query()->where('role', 'admin')->each(function (User $admin) use ($booking, $payment) {
            $admin->notify(new BookingPaymentSucceededNotification($booking, $payment));
        });
    }

    public function notifyPaymentFailed(Booking $booking, Payment $payment, ?string $reason = null): void
    {
        $booking->loadMissing('user');
        $booking->user?->notify(new BookingPaymentFailedNotification($booking, $payment, $reason));
    }

    public function notifyBookingStatusUpdated(Booking $booking, string $status, ?string $note = null): void
    {
        $booking->loadMissing('user');
        $booking->user?->notify(new BookingStatusUpdatedNotification($booking, $status, $note));
    }

    public function notifyPaymentProofReviewed(Booking $booking, Payment $payment, bool $approved): void
    {
        $booking->loadMissing('user');

        $booking->user?->notify(new PaymentProofReviewedNotification(
            $booking,
            $payment,
            $approved,
            $payment->proof_review_notes,
            $payment->proof_reviewer_role
        ));
    }
}
