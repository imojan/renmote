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
}
