<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user customer untuk admin.
     */
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $filter = $request->query('filter', 'all');

        // Tandai notifikasi user baru sebagai sudah dibaca saat admin membuka halaman user.
        $request->session()->put('admin_users_last_seen_at', now()->toDateTimeString());

        $usersQuery = User::query()
            ->where('role', 'user')
            ->withTrashed()
            ->withCount(['bookings', 'wishlists'])
            ->when($filter === 'active', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->when($filter === 'deleted', function ($query) {
                $query->onlyTrashed();
            })
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($inner) use ($keyword) {
                    $inner->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('username', 'like', "%{$keyword}%")
                        ->orWhere('phone_number', 'like', "%{$keyword}%");
                });
            });

        if ($filter === 'deleted') {
            $usersQuery->orderByDesc('deleted_at');
        } else {
            $usersQuery->latest();
        }

        $users = $usersQuery
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'all' => User::withTrashed()->where('role', 'user')->count(),
            'active' => User::where('role', 'user')->count(),
            'deleted' => User::onlyTrashed()->where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'keyword', 'filter', 'stats'));
    }

    /**
     * Detail user untuk admin (termasuk user yang sudah dihapus).
     */
    public function show(int $user)
    {
        $user = User::withTrashed()
            ->where('role', 'user')
            ->with([
                'addresses.district',
                'userDocuments',
                'bookings' => fn ($query) => $query->with('vehicle.vendor', 'payment')->latest()->take(10),
            ])
            ->findOrFail($user);

        $summary = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_addresses' => Address::where('user_id', $user->id)->count(),
            'total_documents' => $user->userDocuments->count(),
            'total_wishlist' => $user->wishlists()->count(),
        ];

        return view('admin.users.show', compact('user', 'summary'));
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

        if ($user->trashed()) {
            return back()->with('error', 'User ini sudah pernah dihapus sebelumnya.');
        }

        try {
            DB::transaction(function () use ($user) {
                DB::table('sessions')->where('user_id', $user->id)->delete();

                $deletedStamp = now()->format('YmdHis') . '_' . $user->id . '_' . Str::random(5);
                $user->email = "deleted_{$deletedStamp}@renmote.local";
                $user->username = 'deleted_' . $deletedStamp;
                $user->phone_number = null;
                $user->save();

                $user->delete();
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'User gagal dihapus. Silakan coba lagi.');
        }

        return back()->with('success', 'User berhasil dihapus.');
    }
}
