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
    ];

    /**
     * Payment dimiliki oleh satu Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
