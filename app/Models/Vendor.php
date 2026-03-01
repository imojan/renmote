<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'user_id',
        'district_id',
        'store_name',
        'description',
        'phone',
        'address',
        'status',
        'verified',
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
}
