@extends('layouts.dashboard')

@section('title', 'Semua Booking')

@section('sidebar')
    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.vendors.index') }}" :active="request()->routeIs('admin.vendors.*')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Vendor
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.vehicles.index') }}" :active="request()->routeIs('admin.vehicles.*')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Kendaraan
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.bookings.index') }}" :active="request()->routeIs('admin.bookings.*')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Booking
    </x-sidebar-link>
@endsection

@section('content')
    @php
        $currentStatus = request('status');
        $currentSortBy = $sortBy ?? request('sort_by', 'id');
        $currentSortDir = $sortDir ?? request('sort_dir', 'desc');
        $nextSortDir = $currentSortDir === 'asc' ? 'desc' : 'asc';
        $directionLabel = $currentSortDir === 'asc' ? 'Ascending' : 'Descending';
        $toggleDirectionLabel = $currentSortDir === 'asc' ? 'Ubah ke Desc' : 'Ubah ke Asc';

        $statusOptions = [
            '' => 'Semua',
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'declined' => 'Declined',
            'completed' => 'Completed',
        ];

        $sortOptions = [
            'id' => 'ID',
            'customer_name' => 'Pelanggan',
            'vehicle_name' => 'Kendaraan',
            'vendor_name' => 'Vendor',
            'booking_date' => 'Tanggal',
            'total_paid' => 'Total',
        ];
    @endphp

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Semua Booking</h2>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.bookings.export', request()->only(['status', 'sort_by', 'sort_dir'])) }}"
               class="px-3 py-1 rounded-lg text-sm bg-emerald-600 text-white hover:bg-emerald-700">
                Export XLSX
            </a>
        </div>
    </div>

    <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select id="status" name="status" class="px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 min-w-40">
                    @foreach($statusOptions as $statusValue => $statusLabel)
                        <option value="{{ $statusValue }}" {{ ($currentStatus ?? '') === $statusValue ? 'selected' : '' }}>
                            {{ $statusLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sort_by" class="block text-xs font-medium text-gray-600 mb-1">Urut Berdasarkan</label>
                <select id="sort_by" name="sort_by" class="px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 min-w-44">
                    @foreach($sortOptions as $sortKey => $sortLabel)
                        <option value="{{ $sortKey }}" {{ $currentSortBy === $sortKey ? 'selected' : '' }}>
                            {{ $sortLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="sort_dir" value="{{ $currentSortDir }}">

            <button type="submit" class="px-4 py-2 rounded-lg text-sm bg-slate-800 text-white hover:bg-slate-900">
                Terapkan
            </button>

            <a href="{{ route('admin.bookings.index', array_filter([
                'status' => $currentStatus,
                'sort_by' => $currentSortBy,
                'sort_dir' => $nextSortDir,
            ], fn ($value) => $value !== null && $value !== '')) }}"
               class="px-4 py-2 rounded-lg text-sm border border-gray-300 text-gray-700 hover:bg-gray-50">
                {{ $directionLabel }} ({{ $toggleDirectionLabel }})
            </a>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow">
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kendaraan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    #{{ $booking->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->vehicle->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->vehicle->vendor->store_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - 
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $booking->status === 'cancelled' ? 'Declined' : ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-gray-500">Tidak ada booking ditemukan.</p>
            </div>
        @endif
    </div>
@endsection
