<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    protected $fillable = [
        'vendor_id',
        'type',
        'file_path',
        'status',
        'notes',
    ];

    /**
     * Document dimiliki oleh satu Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
