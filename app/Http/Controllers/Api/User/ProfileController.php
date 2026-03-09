<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * GET /api/user/profile
     *
     * Menampilkan profil user yang sedang login.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('addresses.district');

        return response()->json([
            'success' => true,
            'data' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'role'              => $user->role,
                'phone_number'      => $user->phone_number,
                'is_phone_verified' => $user->is_phone_verified,
                'email_verified_at' => $user->email_verified_at,
                'addresses'         => $user->addresses->map(fn ($addr) => [
                    'id'          => $addr->id,
                    'label'       => $addr->label,
                    'street'      => $addr->street,
                    'district'    => $addr->district?->name,
                    'city'        => $addr->city,
                    'postal_code' => $addr->postal_code,
                    'is_default'  => $addr->is_default,
                ]),
                'created_at' => $user->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * PUT /api/user/profile
     *
     * Update profil user (name, email, phone_number, password).
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'         => ['sometimes', 'string', 'max:255'],
            'email'        => ['sometimes', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['sometimes', 'nullable', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'password'     => ['sometimes', 'confirmed', Password::defaults()],
        ]);

        // Jika update password, hash dulu
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Jika update phone, reset verifikasi
        if (isset($validated['phone_number']) && $validated['phone_number'] !== $user->phone_number) {
            $validated['is_phone_verified'] = false;
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'phone_number'      => $user->phone_number,
                'is_phone_verified' => $user->is_phone_verified,
            ],
        ]);
    }
}
