<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Halaman detail vendor
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('district', 'user', 'vehicles');
        
        return view('front.vendors.show', compact('vendor'));
    }
}
