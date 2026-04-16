<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\Vendor;
use App\Models\VendorDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Arsip dokumen terpusat untuk admin.
     * Dikelompokkan berdasarkan role: vendor dan user.
     */
    public function index(Request $request)
    {
        $role = $request->query('role', 'all');
        $status = $request->query('status', 'all');
        $keyword = trim((string) $request->query('q', ''));

        $vendorOwners = collect();
        $userOwners = collect();

        if (in_array($role, ['all', 'vendor'], true)) {
            $vendorOwners = Vendor::query()
                ->with([
                    'user:id,name,email',
                    'district:id,name',
                    'documents' => function ($query) use ($status) {
                        if ($status !== 'all') {
                            $query->where('status', $status);
                        }
                        $query->latest();
                    },
                ])
                ->whereHas('documents', function ($query) use ($status) {
                    if ($status !== 'all') {
                        $query->where('status', $status);
                    }
                })
                ->when($keyword !== '', function ($query) use ($keyword) {
                    $query->where(function ($inner) use ($keyword) {
                        $inner->where('store_name', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%")
                            ->orWhereHas('user', function ($q) use ($keyword) {
                                $q->where('name', 'like', "%{$keyword}%")
                                    ->orWhere('email', 'like', "%{$keyword}%");
                            });
                    });
                })
                ->latest()
                ->get();
        }

        if (in_array($role, ['all', 'user'], true)) {
            $userOwners = User::query()
                ->where('role', 'user')
                ->with([
                    'userDocuments' => function ($query) use ($status) {
                        if ($status !== 'all') {
                            $query->where('status', $status);
                        }
                        $query->latest();
                    },
                ])
                ->whereHas('userDocuments', function ($query) use ($status) {
                    if ($status !== 'all') {
                        $query->where('status', $status);
                    }
                })
                ->when($keyword !== '', function ($query) use ($keyword) {
                    $query->where(function ($inner) use ($keyword) {
                        $inner->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('phone_number', 'like', "%{$keyword}%");
                    });
                })
                ->latest()
                ->get();
        }

        $summary = [
            'pending_vendor_documents' => VendorDocument::where('status', 'pending')->count(),
            'pending_user_documents' => UserDocument::where('status', 'pending')->count(),
            'total_vendor_documents' => VendorDocument::count(),
            'total_user_documents' => UserDocument::count(),
        ];

        return view('admin.documents.index', compact('role', 'status', 'keyword', 'vendorOwners', 'userOwners', 'summary'));
    }

    /**
     * Update status dokumen vendor.
     */
    public function updateVendorDocument(Request $request, VendorDocument $document): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $document->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Status dokumen vendor berhasil diperbarui.');
    }

    /**
     * Update status dokumen user.
     */
    public function updateUserDocument(Request $request, UserDocument $document): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $document->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Status dokumen user berhasil diperbarui.');
    }
}
