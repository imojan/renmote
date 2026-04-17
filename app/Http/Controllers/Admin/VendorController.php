<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Notifications\VendorApproved;
use App\Notifications\VendorRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    /**
     * Daftar semua vendors.
     * Query param ?status=pending untuk filter vendor yang belum diverifikasi.
     */
    public function index(Request $request)
    {
        $selectedStatus = $request->status;

        $query = Vendor::query()
            ->with([
                'user' => fn ($q) => $q->withTrashed(),
                'district',
                'documents',
            ]);

        // Filter berdasarkan status vendor
        if ($selectedStatus === 'deleted') {
            $query->onlyTrashed();
        } elseif (in_array($selectedStatus, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $selectedStatus);
        }

        $vendors = $selectedStatus === 'deleted'
            ? $query->orderByDesc('deleted_at')->get()
            : $query->latest()->get();

        // Hitung statistik
        $stats = [
            'total'    => Vendor::withTrashed()->count(),
            'active'   => Vendor::count(),
            'pending'  => Vendor::where('status', 'pending')->count(),
            'approved' => Vendor::where('status', 'approved')->count(),
            'rejected' => Vendor::where('status', 'rejected')->count(),
            'deleted'  => Vendor::onlyTrashed()->count(),
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
        DB::transaction(function () use ($vendor) {
            $vendor->update([
                'verified' => true,
                'status'   => 'approved',
                'rejection_reason' => null,
            ]);

            // Sinkronkan status dokumen agar arsip dokumen ikut approved.
            $vendor->documents()->update([
                'status' => 'approved',
            ]);

            // Kirim notifikasi ke user pemilik vendor.
            $vendor->user->notify(new VendorApproved($vendor));
        });

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

        $reason = $validated['reason'];

        DB::transaction(function () use ($vendor, $reason) {
            $vendor->update([
                'verified' => false,
                'status'   => 'rejected',
                'rejection_reason' => $reason,
            ]);

            // Sinkronkan status dokumen agar konsisten dengan keputusan reject vendor.
            $vendor->documents()->each(function ($document) use ($reason) {
                $document->status = 'rejected';

                if (blank($document->notes)) {
                    $document->notes = $reason;
                }

                $document->save();
            });

            // Kirim notifikasi penolakan ke user pemilik vendor.
            $vendor->user->notify(new VendorRejected($vendor, $reason));
        });

        return back()->with('success', "Vendor '{$vendor->store_name}' berhasil ditolak.");
    }

    /**
     * Hapus vendor beserta dokumen.
     */
    public function destroy(Vendor $vendor)
    {
        try {
            DB::transaction(function () use ($vendor) {
                $vendor->loadMissing('documents', 'user');

                foreach ($vendor->documents as $document) {
                    if ($document->file_path) {
                        Storage::disk('local')->delete($document->file_path);
                    }
                }

                $vendor->vehicles()->update(['status' => 'unavailable']);

                $owner = $vendor->user;

                if (!$vendor->trashed()) {
                    $vendor->delete();
                }

                if ($owner && !$owner->trashed()) {
                    DB::table('sessions')->where('user_id', $owner->id)->delete();

                    $deletedStamp = now()->format('YmdHis') . '_' . $owner->id . '_' . Str::random(5);
                    $owner->email = "deleted_{$deletedStamp}@renmote.local";
                    $owner->username = 'deleted_' . $deletedStamp;
                    $owner->phone_number = null;
                    $owner->save();

                    $owner->delete();
                }
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Vendor gagal dihapus. Pastikan tidak ada data terkait yang masih terkunci.');
        }

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}
