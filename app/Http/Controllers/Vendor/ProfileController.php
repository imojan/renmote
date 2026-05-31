<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\VendorDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Halaman pengaturan profil vendor.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $vendor = $user->vendor()->with('documents', 'district')->first();

        abort_unless($vendor, 403, 'Akun vendor belum terdaftar.');

        $districts = District::orderBy('name')->get();
        $documentsByType = $vendor->documents->keyBy('type');

        return view('vendor.profile.edit', compact('user', 'vendor', 'districts', 'documentsByType'));
    }

    /**
     * Update informasi profil utama (nama owner, email, no HP, foto profil bisnis).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $vendor = $user->vendor;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($user->id),
            ],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
        ])->save();

        if ($request->hasFile('profile_photo')) {
            if ($vendor->profile_photo) {
                Storage::disk('public')->delete($vendor->profile_photo);
            }
            $vendor->profile_photo = $request->file('profile_photo')->store('vendors/profile', 'public');
            $vendor->save();
        }

        return back()->with('success', 'Profil bisnis berhasil diperbarui.');
    }

    /**
     * Update background/cover photo toko.
     */
    public function updateCover(Request $request): RedirectResponse
    {
        $vendor = $request->user()->vendor;

        $request->validate([
            'cover_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($vendor->cover_photo) {
            Storage::disk('public')->delete($vendor->cover_photo);
        }

        $vendor->cover_photo = $request->file('cover_photo')->store('vendors/cover', 'public');
        $vendor->save();

        return back()->with('success', 'Foto background toko berhasil diunggah.');
    }

    /**
     * Hapus background/cover photo.
     */
    public function destroyCover(Request $request): RedirectResponse
    {
        $vendor = $request->user()->vendor;

        if ($vendor->cover_photo) {
            Storage::disk('public')->delete($vendor->cover_photo);
            $vendor->cover_photo = null;
            $vendor->save();
        }

        return back()->with('success', 'Foto background toko dihapus.');
    }

    /**
     * Update info toko + alamat + district.
     */
    public function updateStore(Request $request): RedirectResponse
    {
        $vendor = $request->user()->vendor;

        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'district_id' => ['required', 'exists:districts,id'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        $vendor->update($validated);

        return back()->with('success', 'Informasi toko berhasil diperbarui.');
    }

    /**
     * Update info bank.
     */
    public function updateBank(Request $request): RedirectResponse
    {
        $vendor = $request->user()->vendor;

        $validated = $request->validate([
            'bank_name' => ['nullable', 'string', 'max:60'],
            'bank_account' => ['nullable', 'string', 'max:40'],
        ]);

        $vendor->update($validated);

        return back()->with('success', 'Informasi bank berhasil diperbarui.');
    }

    /**
     * Update rating manual (diambil dari review Maps oleh vendor).
     */
    public function updateRating(Request $request): RedirectResponse
    {
        $vendor = $request->user()->vendor;

        $validated = $request->validate([
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'rating_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $vendor->update([
            'rating' => $validated['rating'] ?? null,
            'rating_count' => $validated['rating_count'] ?? null,
        ]);

        return back()->with('success', 'Rating bisnis berhasil disimpan.');
    }

    /**
     * Upload ulang dokumen (KTP/Surat Izin/Foto Toko).
     */
    public function uploadDocument(Request $request, string $type): RedirectResponse
    {
        $allowed = ['ktp', 'permit', 'photo'];
        abort_unless(in_array($type, $allowed, true), 422);

        $vendor = $request->user()->vendor;

        $request->validate([
            'document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $file = $request->file('document');
        $filename = $type . '_' . Str::random(16) . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("vendor_documents/{$vendor->id}", $filename, 'local');

        $existing = VendorDocument::where('vendor_id', $vendor->id)
            ->where('type', $type)
            ->first();

        if ($existing) {
            if ($existing->file_path) {
                Storage::disk('local')->delete($existing->file_path);
            }
            $existing->update([
                'file_path' => $path,
                'status' => 'pending',
                'notes' => null,
            ]);
        } else {
            VendorDocument::create([
                'vendor_id' => $vendor->id,
                'type' => $type,
                'file_path' => $path,
                'status' => 'pending',
            ]);
        }

        // Kalau vendor sebelumnya rejected, kembalikan ke pending agar admin bisa review ulang.
        if ($vendor->status === 'rejected') {
            $vendor->update([
                'status' => 'pending',
                'verified' => false,
                'rejection_reason' => null,
            ]);
        }

        return back()->with('success', 'Dokumen berhasil diunggah ulang. Menunggu review admin.');
    }

    /**
     * Hapus dokumen vendor.
     */
    public function destroyDocument(Request $request, VendorDocument $document): RedirectResponse
    {
        $vendor = $request->user()->vendor;
        abort_unless($document->vendor_id === $vendor->id, 403);

        if ($document->file_path) {
            Storage::disk('local')->delete($document->file_path);
        }
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Hapus akun vendor secara mandiri.
     */
    public function destroyAccount(Request $request): RedirectResponse
    {
        $request->validateWithBag('vendorDeletion', [
            'confirmation_text' => [
                'required', 'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (mb_strtoupper(trim((string) $value)) !== 'HAPUS AKUN') {
                        $fail('Ketik persis HAPUS AKUN untuk konfirmasi.');
                    }
                },
            ],
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'Password tidak sesuai.',
        ]);

        $user = $request->user();
        $vendor = $user->vendor;

        try {
            DB::transaction(function () use ($user, $vendor) {
                if ($vendor) {
                    $vendor->loadMissing('documents', 'vehicles');

                    foreach ($vendor->documents as $doc) {
                        if ($doc->file_path) Storage::disk('local')->delete($doc->file_path);
                    }
                    foreach ($vendor->vehicles as $vehicle) {
                        if ($vehicle->image) Storage::disk('public')->delete($vehicle->image);
                    }
                    if ($vendor->profile_photo) Storage::disk('public')->delete($vendor->profile_photo);
                    if ($vendor->cover_photo) Storage::disk('public')->delete($vendor->cover_photo);

                    $vendor->vehicles()->update(['status' => 'unavailable']);

                    if (!$vendor->trashed()) {
                        $vendor->delete();
                    }
                }

                if (Schema::hasTable('sessions')) {
                    DB::table('sessions')->where('user_id', $user->id)->delete();
                }

                $stamp = now()->format('YmdHis') . '_' . $user->id . '_' . Str::random(5);
                $user->email = "deleted_{$stamp}@renmote.local";
                $user->username = 'deleted_' . $stamp;
                $user->phone_number = null;
                $user->save();
                $user->delete();
            });
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Akun gagal dihapus. Silakan coba lagi.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Akun vendor kamu berhasil dihapus.');
    }
}
