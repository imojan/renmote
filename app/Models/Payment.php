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
        'gateway_status',
        'gateway_payment_type',
        'gateway_transaction_id',
        'gateway_last_synced_at',
        'gateway_payload',
        'provider',
        'payment_method',
        'invoice_number',
        'snap_token',
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
            'gateway_last_synced_at' => 'datetime',
            'gateway_payload' => 'array',
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
