<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorRegistrationRequest;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDocument;
use App\Events\VendorRegistered;
use App\Notifications\NewVendorRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class VendorRegistrationController extends Controller
{
    /**
     * Store a newly created vendor registration.
     */
    public function store(VendorRegistrationRequest $request)
    {
        $user = $request->user();

        // Pastikan user belum punya vendor
        if ($user->vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar sebagai vendor.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create vendor record
            $vendor = Vendor::create([
                'user_id'      => $user->id,
                'district_id'  => $request->district_id,
                'store_name'   => $request->store_name,
                'description'  => $request->description,
                'phone'        => $request->phone,
                'address'      => $request->address,
                'bank_name'    => $request->bank_name,
                'bank_account' => $request->bank_account,
                'verified'     => false,
                'status'       => 'pending',
            ]);

            // Handle file uploads → storage/app/private/vendor_documents/{vendor_id}/
            $documentTypes = ['ktp', 'permit', 'photo'];

            foreach ($documentTypes as $type) {
                if ($request->hasFile($type)) {
                    $path = $this->storeDocument($request->file($type), $type, $vendor->id);

                    VendorDocument::create([
                        'vendor_id' => $vendor->id,
                        'type'      => $type,
                        'file_path' => $path,
                        'status'    => 'pending',
                    ]);
                }
            }

            // Update user role to vendor
            $user->update(['role' => 'vendor']);

            DB::commit();

            // Fire event (stub — listeners can be added later)
            VendorRegistered::dispatch($vendor);

            // Notify admin users (stub)
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewVendorRegistration($vendor));
            }

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran vendor berhasil! Menunggu verifikasi admin.',
                'data'    => [
                    'vendor_id'  => $vendor->id,
                    'store_name' => $vendor->store_name,
                    'status'     => $vendor->status,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendaftar vendor.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Store a document file with hashed unique filename.
     *
     * Validates MIME type & size, then stores in storage/app/private/vendor_documents/{vendorId}/
     * using a unique filename: {type}_{hash}_{timestamp}.{ext}
     */
    private function storeDocument($file, string $type, int $vendorId): string
    {
        // Validate MIME type
        $allowedMimes = [
            'ktp'    => ['image/jpeg', 'image/png', 'application/pdf'],
            'permit' => ['image/jpeg', 'image/png', 'application/pdf'],
            'photo'  => ['image/jpeg', 'image/png'],
        ];

        $mime = $file->getMimeType();
        if (!in_array($mime, $allowedMimes[$type] ?? [])) {
            throw new \InvalidArgumentException("Tipe file tidak valid untuk {$type}: {$mime}");
        }

        // Validate size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new \InvalidArgumentException("Ukuran file {$type} melebihi 2MB.");
        }

        // Generate unique filename: {type}_{hash}_{timestamp}.{ext}
        $extension = $file->getClientOriginalExtension();
        $hash = Str::random(16);
        $timestamp = now()->format('Ymd_His');
        $filename = "{$type}_{$hash}_{$timestamp}.{$extension}";

        // Store in storage/app/private/vendor_documents/{vendorId}/
        $directory = "vendor_documents/{$vendorId}";
        $path = $file->storeAs($directory, $filename, 'local');

        return $path;
    }

    /**
     * Generate a signed temporary URL for admin to review a vendor document.
     *
     * Note: temporaryUrl() requires a cloud disk driver (S3, GCS, etc).
     * For local disk, we use a signed route fallback instead.
     */
    public function reviewDocument(Request $request, VendorDocument $document)
    {
        // Option A: If using cloud storage (S3/GCS), use temporaryUrl
        // $url = Storage::disk('s3')->temporaryUrl(
        //     $document->file_path,
        //     now()->addMinutes(30)
        // );

        // Option B: For local storage, serve via signed route
        $url = URL::signedRoute('vendor.document.serve', [
            'document' => $document->id,
        ], now()->addMinutes(30));

        return response()->json([
            'success'    => true,
            'url'        => $url,
            'expires_in' => '30 minutes',
            'document'   => [
                'id'   => $document->id,
                'type' => $document->type,
            ],
        ]);
    }

    /**
     * Serve a vendor document file via signed URL (local storage).
     */
    public function serveDocument(Request $request, VendorDocument $document)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Link tidak valid atau sudah kadaluarsa.');
        }

        $path = storage_path('app/private/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($path);
    }
}
