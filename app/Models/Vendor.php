<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'district_id',
        'store_name',
        'description',
        'phone',
        'address',
        'bank_name',
        'bank_account',
        'status',
        'verified',
        'rejection_reason',
    ];

    /**
     * Vendor dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vendor berada di satu District
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Vendor memiliki banyak Vehicles
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Vendor memiliki banyak Documents
     */
    public function documents()
    {
        return $this->hasMany(VendorDocument::class);
    }

    /**
     * Vendor bisa masuk wishlist banyak user.
     */
    public function wishlists()
    {
        return $this->morphMany(Wishlist::class, 'wishlistable');
    }
}
