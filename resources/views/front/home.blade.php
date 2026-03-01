@extends('layouts.front')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Sewa Motor Mudah & Cepat</h1>
            <p class="text-xl text-blue-100 mb-8">Temukan motor terbaik untuk perjalananmu di berbagai kota</p>
            <a href="{{ route('search') }}" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition">
                Cari Motor Sekarang
            </a>
        </div>
    </div>

    <!-- Features -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Mudah Dicari</h3>
                    <p class="text-gray-600">Cari motor berdasarkan lokasi, kategori, dan tanggal dengan mudah</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Vendor Terverifikasi</h3>
                    <p class="text-gray-600">Semua vendor telah diverifikasi untuk keamanan transaksi</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Harga Terjangkau</h3>
                    <p class="text-gray-600">DP hanya 30%, bayar sisanya saat mengambil motor</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Vehicles -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Motor Tersedia</h2>
            
            @if($vehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($vehicles as $vehicle)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                @if($vehicle->image)
                                    <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900">{{ $vehicle->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $vehicle->vendor->store_name }} • {{ $vehicle->vendor->district->name }}</p>
                                <p class="text-sm text-gray-500">{{ $vehicle->category }} • {{ $vehicle->year }}</p>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</span>
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="text-sm text-blue-600 hover:underline">Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-8">
                    <a href="{{ route('search') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Lihat Semua Motor
                    </a>
                </div>
            @else
                <p class="text-center text-gray-500">Belum ada motor tersedia.</p>
            @endif
        </div>
    </div>
@endsection
