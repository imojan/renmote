<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'wishlistable_type',
        'wishlistable_id',
    ];

    /**
     * Wishlist item dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Target wishlist bisa berupa Vehicle atau Vendor.
     */
    public function wishlistable()
    {
        return $this->morphTo();
    }
}
