@extends('layouts.front')

@section('title', $vehicle->name)

@section('content')
    <section class="section front-content-section front-vehicle-section">
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
                        <p class="text-gray-500">
                            {{ ucfirst($vehicle->category) }}
                            @if($vehicle->engine_cc)
                                • {{ $vehicle->engine_cc }}cc
                            @endif
                            • {{ $vehicle->year }}
                        </p>
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

                <div class="mb-6">
                    <div class="flex flex-wrap gap-3">
                        @auth
                            @if(auth()->user()->role === 'user')
                                <form action="{{ route('user.wishlist.vehicles.toggle', $vehicle) }}" method="POST" data-rn-wishlist>
                                    @csrf
                                    <button type="submit" class="btn-fav inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border transition-all {{ $isWishlistedVehicle ? 'is-active border-red-300 text-red-600 bg-red-50' : 'border-gray-300 text-gray-600 bg-white hover:border-red-300 hover:text-red-600' }} text-sm font-medium">
                                        <i class="fa fa-heart"></i>
                                        <span>{{ $isWishlistedVehicle ? 'Difavoritkan' : 'Favoritkan' }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('user.wishlist.vendors.toggle', $vehicle->vendor) }}" method="POST" data-rn-wishlist>
                                    @csrf
                                    <button type="submit" class="btn-fav inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border transition-all {{ $isWishlistedVendor ? 'is-active border-red-300 text-red-600 bg-red-50' : 'border-gray-300 text-gray-600 bg-white hover:border-red-300 hover:text-red-600' }} text-sm font-medium">
                                        <i class="fa fa-store"></i>
                                        <span>{{ $isWishlistedVendor ? 'Vendor Difavoritkan' : 'Favoritkan Vendor' }}</span>
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 text-gray-600 bg-white hover:border-red-300 hover:text-red-600 transition-all text-sm font-medium">
                                <i class="fa fa-heart"></i>
                                <span>Login untuk Wishlist</span>
                            </a>
                        @endauth
                    </div>
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
                    <a href="{{ route('vendors.show', $vehicle->vendor) }}" class="flex items-center hover:bg-gray-50 p-3 rounded-lg -mx-3 transition">
                        @php
                            $vendorInitial = strtoupper(substr($vehicle->vendor->store_name, 0, 1));
                        @endphp
                        
                        @if($vehicle->vendor->profile_photo)
                            <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 mr-4">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($vehicle->vendor->profile_photo) }}" 
                                     alt="{{ $vehicle->vendor->store_name }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center\'><span class=\'text-xl font-bold text-white\'>{{ $vendorInitial }}</span></div>';">
                            </div>
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mr-4">
                                <span class="text-xl font-bold text-white">{{ $vendorInitial }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">
                                {{ $vehicle->vendor->store_name }}
                                @if($vehicle->vendor->verified)
                                    <span class="ml-1 text-green-500" title="Terverifikasi"><i class="fa fa-check-circle"></i></span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 truncate">{{ $vehicle->vendor->district->name }}</p>
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
    </section>
@endsection
