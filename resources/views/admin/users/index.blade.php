@extends('layouts.dashboard')

@section('title', 'Kelola User')

@section('content')
    <div class="dash-card mb-5">
        <div class="dash-card-header">
            <h3 class="dash-card-title">Daftar User</h3>
        </div>

        <div class="dash-card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_180px] gap-3 md:items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Cari User</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $keyword }}"
                        class="w-full h-12 rounded-lg border-slate-300 text-sm"
                        placeholder="Nama, username, email, telepon"
                    >
                </div>
                <div>
                    <button type="submit" class="w-full h-12 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        Cari User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="dash-card">
        <div class="dash-card-header">
            <h3 class="dash-card-title">User Terdaftar</h3>
            <span class="text-xs text-slate-500">Total: {{ $users->total() }} user</span>
        </div>

        <div class="dash-card-body">
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $user->username ? '@'.$user->username : '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700">
                                        <div>{{ $user->email }}</div>
                                        <div class="text-xs text-slate-500">{{ $user->phone_number ?: '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700">
                                        <div>{{ $user->bookings_count }} booking</div>
                                        <div class="text-xs text-slate-500">{{ $user->wishlists_count }} wishlist</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            data-confirm-title="Hapus user ini?"
                                            data-confirm-message="User {{ $user->name }} akan dihapus permanen dari platform."
                                            data-confirm-confirm-text="Ya, Hapus"
                                            data-confirm-cancel-text="Batal"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="dash-empty">
                    <p>Tidak ada user ditemukan untuk pencarian saat ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
