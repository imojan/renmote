@extends('layouts.dashboard')

@section('title', 'Detail Pesanan')

@section('sidebar')
    <x-sidebar-link href="{{ route('vendor.dashboard') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('vendor.vehicles.index') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Kendaraan
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('vendor.bookings.index') }}" :active="true">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Pesanan
    </x-sidebar-link>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('vendor.bookings.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Detail Pesanan</h2>
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                    @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ $booking->status === 'cancelled' ? 'Declined' : ucfirst($booking->status) }}
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
                    <div>
                        <p class="text-sm text-gray-500">Nomor Telepon</p>
                        <p class="font-semibold">{{ $booking->user->phone_number ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Metode Pengambilan</p>
                        <p class="font-semibold">{{ $booking->fulfillment_method === 'delivery' ? 'Diantar ke alamat user' : 'Ambil di outlet/vendor' }}</p>
                    </div>
                </div>

                @if($booking->fulfillment_method === 'delivery')
                    @php
                        $deliveryAddress = $booking->delivery_address_snapshot ?? [];
                    @endphp
                    <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-3">
                        <h4 class="font-semibold text-blue-900 mb-1">Alamat Pengantaran</h4>
                        <p class="text-sm text-blue-900">
                            {{ $deliveryAddress['label'] ?? optional($booking->address)->label ?? '-' }}
                            •
                            {{ (($deliveryAddress['address_type'] ?? optional($booking->address)->address_type) === 'temporary') ? 'Sementara' : 'Tetap' }}
                        </p>
                        <p class="text-sm text-blue-800 mt-1">
                            {{ $deliveryAddress['street'] ?? optional($booking->address)->street ?? '-' }},
                            {{ $deliveryAddress['district'] ?? optional(optional($booking->address)->district)->name ?? '-' }},
                            {{ $deliveryAddress['city'] ?? optional($booking->address)->city ?? '-' }}
                            {{ $deliveryAddress['postal_code'] ?? optional($booking->address)->postal_code ?? '-' }}
                        </p>
                    </div>
                @endif

                <div class="mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Dokumen Pendukung User</h4>
                    @if($booking->user->userDocuments->count() > 0)
                        <div class="space-y-2">
                            @foreach($booking->user->userDocuments as $document)
                                <div class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $document->type === 'ktp' ? 'KTP/KTM' : strtoupper($document->type) }}</p>
                                        <p class="text-xs text-gray-500">Status: {{ ucfirst($document->status) }}</p>
                                    </div>
                                    <a href="{{ route('documents.user.media', $document) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat Dokumen</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">User belum mengunggah dokumen pendukung.</p>
                    @endif
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="flex items-start mb-6 pb-6 border-b">
                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                    @if($booking->vehicle->image)
                        <img src="{{ Storage::url($booking->vehicle->image) }}" alt="{{ $booking->vehicle->name }}" class="w-full h-full object-cover rounded-lg">
                    @else
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $booking->vehicle->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $booking->vehicle->category }} • {{ $booking->vehicle->year }}</p>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b">
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
                    <p class="font-semibold">{{ max(1, \Carbon\Carbon::parse($booking->start_date)->diffInDays($booking->end_date)) }} hari</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Harga</p>
                    <p class="font-semibold text-lg text-blue-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Payment Info -->
            @if($booking->payment)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Pembayaran</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">No. Invoice</span>
                            <span class="font-medium">{{ $booking->payment->invoice_number ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-medium">{{ strtoupper($booking->payment->payment_method ?? 'qris') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tipe Pembayaran</span>
                            <span class="font-medium">{{ strtoupper($booking->payment->payment_type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah Dibayar</span>
                            <span class="font-medium">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $booking->payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($booking->payment->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status Bukti Pembayaran</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $booking->payment->proof_status === 'uploaded' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->payment->proof_status ?? 'not_uploaded')) }}
                            </span>
                        </div>
                        @if($booking->payment->proof_uploaded_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Waktu Upload Bukti</span>
                                <span class="font-medium">{{ $booking->payment->proof_uploaded_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                        @if($booking->payment->proof_path)
                            <div class="pt-2 border-t">
                                <a href="{{ route('documents.payment.proof.media', $booking->payment) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                    Lihat Bukti Pembayaran
                                </a>
                                @if($booking->payment->proof_notes)
                                    <p class="text-xs text-gray-500 mt-1">Catatan user: {{ $booking->payment->proof_notes }}</p>
                                @endif
                            </div>
                        @endif
                        @if($booking->payment->payment_type === 'dp')
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="text-gray-600">Sisa Pembayaran</span>
                                <span class="font-semibold text-red-600">Rp {{ number_format($booking->total_price - $booking->payment->amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex space-x-4">
                @if($booking->status === 'pending')
                    <form action="{{ route('vendor.bookings.confirm', $booking->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                            Konfirmasi Pesanan
                        </button>
                    </form>
                    <form action="{{ route('vendor.bookings.reject', $booking) }}" method="POST" class="flex-1"
                        data-confirm-title="Tolak pesanan?"
                        data-confirm-message="Pesanan ini akan ditolak. Lanjutkan?"
                        data-confirm-confirm-text="Ya, Tolak"
                        data-confirm-cancel-text="Batal">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                            Tolak Pesanan
                        </button>
                    </form>
                @endif
                
                @if($booking->status === 'confirmed')
                    <form action="{{ route('vendor.bookings.complete', $booking) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                            Selesaikan Pesanan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
