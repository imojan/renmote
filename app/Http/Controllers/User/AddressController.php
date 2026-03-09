<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreAddressRequest;
use App\Models\Address;
use App\Models\District;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Daftar alamat milik user.
     */
    public function index()
    {
        $addresses = auth()->user()->addresses()->with('district')->get();

        return view('user.addresses.index', compact('addresses'));
    }

    /**
     * Tampilkan form tambah alamat (atau gunakan modal).
     */
    public function create()
    {
        $districts = District::orderBy('name')->get();

        return view('user.addresses.create', compact('districts'));
    }

    /**
     * Simpan alamat baru.
     */
    public function store(StoreAddressRequest $request)
    {
        $user = $request->user();

        // Jika set sebagai default, unset default lainnya
        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        // Jika ini alamat pertama, otomatis jadikan default
        $isFirst = $user->addresses()->count() === 0;

        $address = $user->addresses()->create([
            'label'       => $request->label,
            'street'      => $request->street,
            'district_id' => $request->district_id,
            'city'        => $request->city,
            'postal_code' => $request->postal_code,
            'is_default'  => $request->boolean('is_default') || $isFirst,
        ]);

        // JSON response untuk AJAX / modal
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil ditambahkan.',
                'data'    => $address->load('district'),
            ], 201);
        }

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Update alamat.
     */
    public function update(StoreAddressRequest $request, Address $address)
    {
        // Pastikan alamat milik user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            'label'       => $request->label,
            'street'      => $request->street,
            'district_id' => $request->district_id,
            'city'        => $request->city,
            'postal_code' => $request->postal_code,
            'is_default'  => $request->boolean('is_default'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil diperbarui.',
                'data'    => $address->load('district'),
            ]);
        }

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Hapus alamat.
     */
    public function destroy(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Set alamat sebagai default.
     */
    public function setDefault(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat default berhasil diubah.',
            ]);
        }

        return redirect()->back()->with('success', 'Alamat default berhasil diubah.');
    }
}
