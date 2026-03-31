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

        // Filter berdasarkan status vendor
        if (in_array($request->status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $request->status);
        }

        $vendors = $query->latest()->get();

        // Hitung statistik
        $stats = [
            'total'    => Vendor::count(),
            'pending'  => Vendor::where('status', 'pending')->count(),
            'approved' => Vendor::where('status', 'approved')->count(),
            'rejected' => Vendor::where('status', 'rejected')->count(),
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
            'rejection_reason' => null,
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
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $vendor->update([
            'verified' => false,
            'status'   => 'rejected',
            'rejection_reason' => $validated['reason'],
        ]);

        $reason = $validated['reason'];

        // Kirim notifikasi penolakan ke user pemilik vendor
        $vendor->user->notify(new VendorRejected($vendor, $reason));

        return back()->with('success', "Vendor '{$vendor->store_name}' berhasil ditolak.");
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
