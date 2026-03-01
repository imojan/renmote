@extends('layouts.dashboard')

@section('title', 'Detail Kendaraan')

@section('sidebar')
    <x-sidebar-link href="{{ route('admin.dashboard') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.vendors.index') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Vendor
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.vehicles.index') }}" :active="true">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Kendaraan
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.bookings.index') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Booking
    </x-sidebar-link>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('admin.vehicles.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $vehicle->name }}</h2>
                    <p class="text-gray-500">{{ ucfirst($vehicle->category) }} • {{ $vehicle->year }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $vehicle->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                </span>
            </div>

            <!-- Image -->
            <div class="mb-6">
                @if($vehicle->image)
                    <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Vendor Info -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-700 mb-3">Informasi Vendor</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $vehicle->vendor->store_name }}</p>
                        <p class="text-sm text-gray-500">{{ $vehicle->vendor->district->name }}</p>
                    </div>
                    <a href="{{ route('admin.vendors.show', $vehicle->vendor) }}" class="text-blue-600 hover:underline text-sm">Lihat Vendor</a>
                </div>
            </div>

            <!-- Description -->
            @if($vehicle->description)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="font-semibold text-gray-700 mb-3">Deskripsi</h3>
                    <p class="text-gray-600">{{ $vehicle->description }}</p>
                </div>
            @endif

            <!-- Details -->
            <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b">
                <div>
                    <p class="text-sm text-gray-500">Harga per Hari</p>
                    <p class="text-xl font-bold text-blue-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Stok</p>
                    <p class="font-semibold">{{ $vehicle->stock }} unit</p>
                </div>
            </div>

            <!-- Booking History -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Riwayat Booking</h3>
                @if($vehicle->bookings->count() > 0)
                    <div class="space-y-2">
                        @foreach($vehicle->bookings->take(5) as $booking)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - 
                                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada booking.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
