<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreVehicleRequest;
use App\Http\Requests\Vendor\UpdateVehicleRequest;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Daftar kendaraan milik vendor
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;
        $vehicles = $vendor->vehicles()->latest()->get();

        return view('vendor.vehicles.index', compact('vehicles'));
    }

    /**
     * Form tambah kendaraan baru
     */
    public function create()
    {
        return view('vendor.vehicles.create');
    }

    /**
     * Simpan kendaraan baru
     */
    public function store(StoreVehicleRequest $request)
    {
        $vendor = auth()->user()->vendor;
        $data = $request->validated();
        $data['status'] = 'available';

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        }

        $vendor->vehicles()->create($data);

        return redirect()->route('vendor.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    /**
     * Form edit kendaraan
     */
    public function edit(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        return view('vendor.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update kendaraan
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return redirect()->route('vendor.vehicles.index')
            ->with('success', 'Kendaraan berhasil diupdate.');
    }

    /**
     * Hapus kendaraan
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $vehicle->delete();

        return redirect()->route('vendor.vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }

    /**
     * Pastikan kendaraan milik vendor yang sedang login
     */
    private function authorizeVehicle(Vehicle $vehicle)
    {
        if ($vehicle->vendor_id !== auth()->user()->vendor->id) {
            abort(403, 'Unauthorized');
        }
    }
}
