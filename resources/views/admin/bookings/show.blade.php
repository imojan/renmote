@extends('layouts.dashboard')

@section('title', 'Detail Booking')

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
    
    <x-sidebar-link href="{{ route('admin.vehicles.index') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Kendaraan
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.bookings.index') }}" :active="true">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Booking
    </x-sidebar-link>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Booking #{{ $booking->id }}</h2>
                    <p class="text-gray-500">{{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                    @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <!-- Customer Info -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-700 mb-3">Informasi Pelanggan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-semibold">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold">{{ $booking->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle & Vendor Info -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-700 mb-3">Kendaraan & Vendor</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">Kendaraan</p>
                        <p class="font-semibold">{{ $booking->vehicle->name }}</p>
                        <p class="text-sm text-gray-600">{{ ucfirst($booking->vehicle->category) }} • {{ $booking->vehicle->year }}</p>
                        <a href="{{ route('admin.vehicles.show', $booking->vehicle) }}" class="text-blue-600 hover:underline text-sm">Lihat Detail</a>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">Vendor</p>
                        <p class="font-semibold">{{ $booking->vehicle->vendor->store_name }}</p>
                        <p class="text-sm text-gray-600">{{ $booking->vehicle->vendor->district->name }}</p>
                        <a href="{{ route('admin.vendors.show', $booking->vehicle->vendor) }}" class="text-blue-600 hover:underline text-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b">
                <div>
                    <p class="text-sm text-gray-500">Tanggal Mulai</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Selesai</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Durasi</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->start_date)->diffInDays($booking->end_date) + 1 }} hari</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Informasi Pembayaran</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Harga</span>
                        <span class="font-semibold text-lg">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($booking->payment)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tipe Pembayaran</span>
                            <span class="font-medium">{{ strtoupper($booking->payment->payment_type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah Dibayar</span>
                            <span class="font-medium">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $booking->payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($booking->payment->status) }}
                            </span>
                        </div>
                        @if($booking->payment->payment_type === 'dp' && $booking->payment->status === 'paid')
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="text-gray-600">Sisa Pembayaran</span>
                                <span class="font-semibold text-red-600">Rp {{ number_format($booking->total_price - $booking->payment->amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500">Belum ada pembayaran.</p>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Booking Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $booking->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($booking->status !== 'pending')
                        <div class="flex items-center">
                            <div class="w-3 h-3 {{ in_array($booking->status, ['confirmed', 'completed']) ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $booking->status === 'cancelled' ? 'Dibatalkan' : 'Dikonfirmasi' }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $booking->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($booking->status === 'completed')
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Selesai</p>
                                <p class="text-xs text-gray-500">{{ $booking->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
