<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user customer untuk admin.
     */
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $users = User::query()
            ->where('role', 'user')
            ->withCount(['bookings', 'wishlists'])
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($inner) use ($keyword) {
                    $inner->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('username', 'like', "%{$keyword}%")
                        ->orWhere('phone_number', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'keyword'));
    }

    /**
     * Hapus user customer oleh admin.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akun sendiri dari halaman ini.');
        }

        if ($user->role !== 'user') {
            return back()->with('error', 'Hanya akun role user yang bisa dihapus dari menu ini.');
        }

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
                        ->where('notifiable_type', User::class)
                        ->where('notifiable_id', $user->id)
                        ->delete();
                }

                if (Schema::hasTable('sessions')) {
                    DB::table('sessions')->where('user_id', $user->id)->delete();
                }

                if (Schema::hasTable('password_reset_tokens')) {
                    DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                }

                $user->delete();
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'User gagal dihapus. Silakan coba lagi.');
        }

        return back()->with('success', 'User berhasil dihapus.');
    }
}
