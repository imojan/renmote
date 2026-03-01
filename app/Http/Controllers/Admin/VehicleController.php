<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Daftar semua vehicles
     */
    public function index()
    {
        $vehicles = Vehicle::with('vendor.user', 'vendor.district')->latest()->get();

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Detail vehicle
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load('vendor.user', 'vendor.district', 'bookings');

        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Hapus vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
