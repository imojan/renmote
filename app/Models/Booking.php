<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'fulfillment_method',
        'address_id',
        'delivery_address_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'delivery_address_snapshot' => 'array',
        ];
    }

    /**
     * Booking dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Booking untuk satu Vehicle
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Booking memiliki satu Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Alamat yang dipilih user untuk pengantaran.
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
