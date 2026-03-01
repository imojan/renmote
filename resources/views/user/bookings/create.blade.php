@extends('layouts.dashboard')

@section('title', 'Booking ' . $vehicle->name)

@section('sidebar')
    <x-sidebar-link href="{{ route('user.dashboard') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('user.bookings.index') }}" :active="true">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Booking Saya
    </x-sidebar-link>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Booking Kendaraan</h2>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <!-- Vehicle Info -->
            <div class="flex items-start mb-6 pb-6 border-b">
                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                    @if($vehicle->image)
                        <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover rounded-lg">
                    @else
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $vehicle->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $vehicle->category }} • {{ $vehicle->year }}</p>
                    <p class="text-sm text-gray-500">{{ $vehicle->vendor->store_name }} - {{ $vehicle->vendor->district->name }}</p>
                    <p class="text-lg font-bold text-blue-600 mt-2">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</p>
                </div>
            </div>

            <!-- Booking Form -->
            <form action="{{ route('user.bookings.store', $vehicle) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-2">Informasi Pembayaran</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• DP (Down Payment) sebesar <strong>30%</strong> dari total harga</li>
                        <li>• Sisa pembayaran dilunasi saat pengambilan motor</li>
                        <li>• Pembatalan gratis jika status masih pending</li>
                    </ul>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        Konfirmasi Booking
                    </button>
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
