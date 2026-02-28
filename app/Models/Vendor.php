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
        'verified',
    ];
}
