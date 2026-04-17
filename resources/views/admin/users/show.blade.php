@extends('layouts.dashboard')

@section('title', 'Detail User')

@section('content')
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali ke Kelola User</a>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->username ? '@'.$user->username : '-' }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $user->trashed() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                    {{ $user->trashed() ? 'Deleted' : 'Active' }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-xs text-gray-500">Email</p>
                    <p class="font-semibold text-gray-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Telepon</p>
                    <p class="font-semibold text-gray-800">{{ $user->phone_number ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Gender</p>
                    <p class="font-semibold text-gray-800">{{ $user->gender ? ucfirst($user->gender) : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Lahir</p>
                    <p class="font-semibold text-gray-800">{{ $user->birth_date ? $user->birth_date->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Bergabung</p>
                    <p class="font-semibold text-gray-800">{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Dihapus</p>
                    <p class="font-semibold text-gray-800">{{ $user->deleted_at ? $user->deleted_at->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50">
                    <p class="text-xs text-slate-500">Total Booking</p>
                    <p class="text-xl font-bold text-slate-800">{{ $summary['total_bookings'] }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50">
                    <p class="text-xs text-slate-500">Completed</p>
                    <p class="text-xl font-bold text-slate-800">{{ $summary['completed_bookings'] }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50">
                    <p class="text-xs text-slate-500">Alamat</p>
                    <p class="text-xl font-bold text-slate-800">{{ $summary['total_addresses'] }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50">
                    <p class="text-xs text-slate-500">Dokumen</p>
                    <p class="text-xl font-bold text-slate-800">{{ $summary['total_documents'] }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50">
                    <p class="text-xs text-slate-500">Wishlist</p>
                    <p class="text-xl font-bold text-slate-800">{{ $summary['total_wishlist'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-3">Dokumen Pendukung</h3>

            @if($user->userDocuments->count() > 0)
                <div class="space-y-3">
                    @foreach($user->userDocuments as $document)
                        <div class="rounded-lg border border-slate-200 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $document->type === 'ktp' ? 'KTP/KTM' : strtoupper($document->type) }}</p>
                                    <p class="text-xs text-slate-500">Status: {{ ucfirst($document->status) }}</p>
                                </div>
                                <a href="{{ route('documents.user.media', $document) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat Dokumen</a>
                            </div>
                            @if($document->notes)
                                <p class="mt-2 text-sm text-slate-600">Catatan: {{ $document->notes }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500">Belum ada dokumen pendukung.</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-3">Alamat User</h3>

            @if($user->addresses->count() > 0)
                <div class="space-y-3">
                    @foreach($user->addresses as $address)
                        <div class="rounded-lg border border-slate-200 p-3">
                            <p class="font-semibold text-slate-800">{{ $address->label }} @if($address->is_default)<span class="text-xs text-blue-600">(Default)</span>@endif</p>
                            <p class="text-sm text-slate-600">{{ $address->street }}, {{ $address->district->name ?? '-' }}, {{ $address->city }} {{ $address->postal_code }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500">Belum ada alamat tersimpan.</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-3">Riwayat Booking Terbaru</h3>

            @if($user->bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Kendaraan</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Vendor</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->bookings as $booking)
                                <tr>
                                    <td class="px-3 py-2 text-sm text-slate-700">#{{ $booking->id }}</td>
                                    <td class="px-3 py-2 text-sm text-slate-700">{{ $booking->vehicle?->name ?? '-' }}</td>
                                    <td class="px-3 py-2 text-sm text-slate-700">{{ $booking->vehicle?->vendor?->store_name ?? '-' }}</td>
                                    <td class="px-3 py-2 text-sm text-slate-700">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
                                    <td class="px-3 py-2 text-sm text-slate-700">{{ ucfirst($booking->status === 'cancelled' ? 'declined' : $booking->status) }}</td>
                                    <td class="px-3 py-2 text-sm">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-slate-500">Belum ada riwayat booking.</p>
            @endif
        </div>
    </div>
@endsection
