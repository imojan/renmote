<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'category',
        'price_per_day',
        'year',
        'description',
        'image',
        'status',
    ];

    /**
     * Vehicle dimiliki oleh satu Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Vehicle memiliki banyak Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
