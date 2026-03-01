<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Halaman detail kendaraan
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load('vendor.district', 'vendor.user');
        
        return view('front.vehicles.show', compact('vehicle'));
    }
}
