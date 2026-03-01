@extends('layouts.front')

@section('title', $vehicle->name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Image -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="h-96 bg-gray-200 flex items-center justify-center">
                    @if($vehicle->image)
                        <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $vehicle->name }}</h1>
                        <p class="text-gray-500">{{ $vehicle->category }} • {{ $vehicle->year }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $vehicle->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>

                <div class="mb-6">
                    <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</span>
                    <span class="text-gray-500">/hari</span>
                </div>

                @if($vehicle->description)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-gray-600">{{ $vehicle->description }}</p>
                    </div>
                @endif

                <!-- Vendor Info -->
                <div class="border-t pt-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Penyedia</h3>
                    <a href="{{ route('vendors.show', $vehicle->vendor) }}" class="flex items-center hover:bg-gray-50 p-3 rounded-lg -mx-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                            <span class="text-xl font-bold text-gray-600">{{ substr($vehicle->vendor->store_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $vehicle->vendor->store_name }}
                                @if($vehicle->vendor->verified)
                                    <span class="ml-1 text-green-500">✓</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $vehicle->vendor->district->name }}</p>
                        </div>
                    </a>
                </div>

                <!-- Book Button -->
                @auth
                    @if(auth()->user()->role === 'user' && $vehicle->status === 'available')
                        <a href="{{ route('user.bookings.create', $vehicle) }}" class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                            Booking Sekarang
                        </a>
                    @elseif(auth()->user()->role !== 'user')
                        <p class="text-center text-gray-500 py-3">Login sebagai user untuk booking</p>
                    @else
                        <button disabled class="block w-full text-center px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed">
                            Tidak Tersedia
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        Login untuk Booking
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection
