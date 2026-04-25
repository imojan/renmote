<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_type',
        'status',
        'provider',
        'payment_method',
        'invoice_number',
        'expires_at',
        'paid_at',
        'proof_path',
        'proof_notes',
        'proof_review_notes',
        'proof_status',
        'proof_uploaded_at',
        'proof_reviewed_at',
        'proof_reviewer_role',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
            'proof_uploaded_at' => 'datetime',
            'proof_reviewed_at' => 'datetime',
        ];
    }

    /**
     * Payment dimiliki oleh satu Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
