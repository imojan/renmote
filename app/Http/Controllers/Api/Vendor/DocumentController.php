<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * POST /api/vendor/documents
     *
     * Upload dokumen vendor (ktp, permit, photo).
     * Gate: user harus role vendor dan punya vendor record.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        // Authorization gate
        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan vendor.',
            ], 403);
        }

        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Data vendor tidak ditemukan. Daftar sebagai vendor terlebih dahulu.',
            ], 404);
        }

        $validated = $request->validate([
            'type' => ['required', 'in:ktp,permit,photo'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'type.required' => 'Tipe dokumen wajib diisi.',
            'type.in'       => 'Tipe dokumen harus ktp, permit, atau photo.',
            'file.required' => 'File wajib diunggah.',
            'file.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        // Cek apakah dokumen tipe ini sudah ada (replace)
        $existing = $vendor->documents()->where('type', $validated['type'])->first();

        // Upload file dengan unique filename
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $hash = Str::random(16);
        $timestamp = now()->format('Ymd_His');
        $filename = "{$validated['type']}_{$hash}_{$timestamp}.{$extension}";

        $path = $file->storeAs(
            "vendor_documents/{$vendor->id}",
            $filename,
            'local'
        );

        if ($existing) {
            // Update existing document
            $existing->update([
                'file_path' => $path,
                'status'    => 'pending', // Reset status for re-review
            ]);

            $document = $existing;
        } else {
            // Create new document
            $document = VendorDocument::create([
                'vendor_id' => $vendor->id,
                'type'      => $validated['type'],
                'file_path' => $path,
                'status'    => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah.',
            'data'    => [
                'id'     => $document->id,
                'type'   => $document->type,
                'status' => $document->status,
            ],
        ], 201);
    }
}
