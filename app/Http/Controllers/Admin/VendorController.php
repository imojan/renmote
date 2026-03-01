<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Daftar semua vendors
     */
    public function index()
    {
        $vendors = Vendor::with('user', 'district')->latest()->get();

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Detail vendor
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('user', 'district', 'vehicles');

        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Verifikasi vendor
     */
    public function verify(Vendor $vendor)
    {
        $vendor->update(['verified' => true]);

        return back()->with('success', 'Vendor berhasil diverifikasi.');
    }

    /**
     * Unverify vendor
     */
    public function unverify(Vendor $vendor)
    {
        $vendor->update(['verified' => false]);

        return back()->with('success', 'Verifikasi vendor dicabut.');
    }

    /**
     * Hapus vendor
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}
