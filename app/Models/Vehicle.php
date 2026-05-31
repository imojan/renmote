<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** Available vehicle categories. Mirrors the search slug map. */
    public const CATEGORIES = [
        'matic'           => 'Matic',
        'manual'          => 'Manual',
        'sport'           => 'Sport',
        'bebek'           => 'Bebek',
        'trail'           => 'Trail',
        'skutik_premium'  => 'Skutik Premium',
        'bigbike'         => 'Big Bike',
    ];

    protected $fillable = [
        'vendor_id',
        'name',
        'category',
        'price_per_day',
        'year',
        'engine_cc',
        'description',
        'image',
        'stock',
        'status',
    ];

    /**
     * Vehicle dimiliki oleh satu Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withTrashed();
    }

    /**
     * Vehicle memiliki banyak Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Vehicle bisa masuk wishlist banyak user.
     */
    public function wishlists()
    {
        return $this->morphMany(Wishlist::class, 'wishlistable');
    }
}
