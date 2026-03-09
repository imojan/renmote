<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * GET /api/vendor/profile
     *
     * Menampilkan profil vendor beserta statistik.
     * Gate: user harus role vendor dan punya vendor record.
     */
    public function show(Request $request): JsonResponse
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
                'message' => 'Data vendor tidak ditemukan.',
            ], 404);
        }

        $vendor->load('district', 'documents', 'vehicles');

        return response()->json([
            'success' => true,
            'data' => [
                'id'           => $vendor->id,
                'store_name'   => $vendor->store_name,
                'description'  => $vendor->description,
                'phone'        => $vendor->phone,
                'address'      => $vendor->address,
                'district'     => $vendor->district?->name,
                'bank_name'    => $vendor->bank_name,
                'bank_account' => $vendor->bank_account,
                'verified'     => $vendor->verified,
                'status'       => $vendor->status,
                'documents'    => $vendor->documents->map(fn ($doc) => [
                    'id'     => $doc->id,
                    'type'   => $doc->type,
                    'status' => $doc->status,
                ]),
                'stats' => [
                    'total_vehicles' => $vendor->vehicles->count(),
                    'active_vehicles' => $vendor->vehicles->where('is_available', true)->count(),
                ],
                'created_at' => $vendor->created_at->toISOString(),
            ],
        ]);
    }
}
