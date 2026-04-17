<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreAddressRequest;
use App\Models\Address;
use App\Models\District;
use App\Models\UserDocument;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Wishlist;
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

class AccountController extends Controller
{
    /**
     * Halaman akun user dalam layout front.
     */
    public function index(Request $request)
    {
        $user = $request->user()->load(['addresses.district', 'userDocuments']);
        $districts = District::query()->orderBy('name')->get();
        $bookings = $request->user()->bookings()
            ->with('vehicle.vendor', 'payment')
            ->latest()
            ->take(8)
            ->get();

        $wishlistVehicles = $request->user()->wishlists()
            ->where('wishlistable_type', Vehicle::class)
            ->with('wishlistable.vendor.district')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (Wishlist $wishlist) => $wishlist->wishlistable)
            ->filter();

        $wishlistVendors = $request->user()->wishlists()
            ->where('wishlistable_type', Vendor::class)
            ->with('wishlistable.district')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (Wishlist $wishlist) => $wishlist->wishlistable)
            ->filter();

        $documentsByType = $user->userDocuments->keyBy('type');

        return view('front.account.index', compact('user', 'districts', 'bookings', 'documentsByType', 'wishlistVehicles', 'wishlistVendors'));
    }

    /**
     * Update profil dasar user (nama, email, kontak).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => [
                'nullable',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->whereNull('deleted_at')->ignore($request->user()->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($request->user()->id),
            ],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $emailChanged = $user->email !== $validated['email'];

        $validated['username'] = $validated['username'] ?: null;

        $user->fill([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->profile_photo_path = $request->file('profile_photo')->store('users/profile', 'public');
        }

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Profil akun berhasil diperbarui.');
    }

    /**
     * Tambah alamat baru dari halaman akun front.
     */
    public function storeAddress(StoreAddressRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        $isFirstAddress = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'label' => $request->label,
            'address_type' => $request->address_type,
            'street' => $request->street,
            'district_id' => $request->district_id,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'is_default' => $request->boolean('is_default') || $isFirstAddress,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Update alamat user dari halaman akun front.
     */
    public function updateAddress(StoreAddressRequest $request, Address $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($request->boolean('is_default')) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            'label' => $request->label,
            'address_type' => $request->address_type,
            'street' => $request->street,
            'district_id' => $request->district_id,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'is_default' => $request->boolean('is_default') || $address->is_default,
        ]);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Hapus alamat user dari halaman akun front.
     */
    public function destroyAddress(Request $request, Address $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextAddress = $request->user()->addresses()->oldest()->first();
            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Ubah alamat default user.
     */
    public function setDefaultAddress(Request $request, Address $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat default berhasil diperbarui.');
    }

    /**
     * Upload dokumen penting user (KTP wajib saat upload pertama, SIM opsional).
     */
    public function updateDocuments(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'document_sim' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        if (!$request->hasFile('document_ktp') && !$request->hasFile('document_sim')) {
            return back()->with('error', 'Pilih minimal satu dokumen untuk diunggah.');
        }

        $filesByType = [
            'ktp' => $request->file('document_ktp'),
            'sim' => $request->file('document_sim'),
        ];

        foreach ($filesByType as $type => $file) {
            if (!$file) {
                continue;
            }

            $existing = $request->user()->userDocuments()->where('type', $type)->first();

            if ($existing && $existing->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }

            $path = $file->store('users/documents', 'public');

            UserDocument::updateOrCreate(
                [
                    'user_id' => $request->user()->id,
                    'type' => $type,
                ],
                [
                    'file_path' => $path,
                    'status' => 'pending',
                    'notes' => null,
                ]
            );
        }

        return back()->with('success', 'Dokumen berhasil diunggah dan menunggu review admin/vendor.');
    }

    /**
     * Hapus dokumen penting user agar bisa upload ulang.
     */
    public function destroyDocument(Request $request, string $type): RedirectResponse
    {
        if (!in_array($type, ['ktp', 'sim'], true)) {
            abort(404);
        }

        $document = $request->user()->userDocuments()->where('type', $type)->first();

        if (!$document) {
            return back()->with('error', 'Dokumen tidak ditemukan atau sudah dihapus.');
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        $documentLabel = $type === 'ktp' ? 'KTP/KTM' : 'SIM';

        return back()->with('success', "Dokumen {$documentLabel} berhasil dihapus. Kamu bisa mengunggah ulang.");
    }

    /**
     * Ubah password user dari halaman akun.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Hapus akun user sendiri dari halaman akun (dengan konfirmasi teks + password).
     */
    public function destroyAccount(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'confirmation_text' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (mb_strtoupper(trim((string) $value)) !== 'HAPUS AKUN') {
                        $fail('Ketik persis HAPUS AKUN untuk konfirmasi.');
                    }
                },
            ],
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'Password tidak sesuai dengan akun kamu.',
        ]);

        $user = $request->user();

        try {
            DB::transaction(function () use ($user) {
                $user->loadMissing(['userDocuments', 'vendor.documents', 'vendor.vehicles']);

                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                foreach ($user->userDocuments as $document) {
                    if ($document->file_path) {
                        Storage::disk('public')->delete($document->file_path);
                    }
                }

                if ($user->vendor) {
                    foreach ($user->vendor->documents as $document) {
                        if ($document->file_path) {
                            Storage::disk('local')->delete($document->file_path);
                        }
                    }

                    foreach ($user->vendor->vehicles as $vehicle) {
                        if ($vehicle->image) {
                            Storage::disk('public')->delete($vehicle->image);
                        }
                    }
                }

                if (Schema::hasTable('notifications')) {
                    DB::table('notifications')
                        ->where('notifiable_type', \App\Models\User::class)
                        ->where('notifiable_id', $user->id)
                        ->delete();
                }

                if (Schema::hasTable('sessions')) {
                    DB::table('sessions')->where('user_id', $user->id)->delete();
                }

                if (Schema::hasTable('password_reset_tokens')) {
                    DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                }

                $deletedStamp = now()->format('YmdHis') . '_' . $user->id . '_' . Str::random(5);
                $user->email = "deleted_{$deletedStamp}@renmote.local";
                $user->username = 'deleted_' . $deletedStamp;
                $user->phone_number = null;
                $user->save();

                $user->delete();
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Akun gagal dihapus. Silakan coba lagi.');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Akun kamu berhasil dihapus.');
    }
}
