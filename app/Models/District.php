<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * District memiliki banyak Vendors
     */
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
}
