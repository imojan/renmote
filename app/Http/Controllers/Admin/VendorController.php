<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Notifications\VendorApproved;
use App\Notifications\VendorRejected;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Daftar semua vendors.
     * Query param ?status=pending untuk filter vendor yang belum diverifikasi.
     */
    public function index(Request $request)
    {
        $query = Vendor::with('user', 'district', 'documents');

        // Filter berdasarkan status verifikasi
        if ($request->status === 'pending') {
            $query->where('verified', false);
        } elseif ($request->status === 'verified') {
            $query->where('verified', true);
        }

        $vendors = $query->latest()->get();

        // Hitung statistik
        $stats = [
            'total'    => Vendor::count(),
            'pending'  => Vendor::where('verified', false)->count(),
            'verified' => Vendor::where('verified', true)->count(),
        ];

        return view('admin.vendors.index', compact('vendors', 'stats'));
    }

    /**
     * Detail vendor beserta dokumen yang diupload.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('user', 'district', 'vehicles', 'documents');

        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Verifikasi (approve) vendor — set verified=true, kirim notifikasi ke vendor.
     */
    public function verify(Vendor $vendor)
    {
        $vendor->update([
            'verified' => true,
            'status'   => 'approved',
        ]);

        // Kirim notifikasi ke user pemilik vendor
        $vendor->user->notify(new VendorApproved($vendor));

        return back()->with('success', "Vendor '{$vendor->store_name}' berhasil diverifikasi.");
    }

    /**
     * Tolak (reject) vendor — set verified=false, kirim notifikasi dengan alasan.
     */
    public function unverify(Vendor $vendor, Request $request)
    {
        $vendor->update([
            'verified' => false,
            'status'   => 'rejected',
        ]);

        $reason = $request->input('reason', '');

        // Kirim notifikasi penolakan ke user pemilik vendor
        $vendor->user->notify(new VendorRejected($vendor, $reason));

        return back()->with('success', "Verifikasi vendor '{$vendor->store_name}' dicabut.");
    }

    /**
     * Hapus vendor beserta dokumen.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}
