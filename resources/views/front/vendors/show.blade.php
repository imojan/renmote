@extends('layouts.front')

@section('title', $vendor->store_name)

@section('content')
    <section class="section front-content-section front-vendor-section">
        <!-- Vendor Header (with cover photo background) -->
        <div class="overflow-hidden rounded-lg shadow mb-8 bg-white">
            @if($vendor->cover_photo)
                <div class="h-44 sm:h-56 w-full">
                    <img src="{{ Storage::url($vendor->cover_photo) }}" alt="{{ $vendor->store_name }}" class="h-full w-full object-cover">
                </div>
            @endif

            <div class="p-6">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shrink-0 {{ $vendor->cover_photo ? '-mt-16 border-4 border-white shadow' : '' }}">
                            @if($vendor->profile_photo)
                                <img src="{{ Storage::url($vendor->profile_photo) }}" alt="{{ $vendor->store_name }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-3xl font-bold text-white">{{ strtoupper(substr($vendor->store_name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center flex-wrap gap-2">
                                <span>{{ $vendor->store_name }}</span>
                                @if($vendor->verified)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full inline-flex items-center gap-1">
                                        <i class="fa fa-check-circle"></i> Terverifikasi
                                    </span>
                                @endif
                            </h1>
                            <p class="text-gray-500 mt-1"><i class="fa fa-map-marker-alt text-xs mr-1"></i>{{ $vendor->district->name }}</p>
                            @if($vendor->rating)
                                <p class="mt-1 text-sm font-semibold text-gray-700">
                                    <i class="fa fa-star text-amber-400"></i>
                                    {{ number_format($vendor->rating, 1) }}
                                    <span class="text-xs font-normal text-gray-500">({{ number_format($vendor->rating_count ?? 0) }} review)</span>
                                </p>
                            @endif
                            @if($vendor->description)
                                <p class="text-gray-600 mt-2 text-sm">{{ $vendor->description }}</p>
                            @endif
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->role === 'user')
                            <div class="flex items-center gap-3">
                                <form action="{{ route('user.wishlist.vendors.toggle', $vendor) }}" method="POST" data-rn-wishlist>
                                    @csrf
                                    <button type="submit" class="btn-fav flex items-center justify-center w-11 h-11 rounded-xl border transition-all {{ $isWishlistedVendor ? 'is-active border-red-300 text-red-600 bg-red-50' : 'border-gray-300 text-gray-500 hover:border-red-300 hover:text-red-600' }}">
                                        <i class="fa fa-heart text-lg"></i>
                                    </button>
                                </form>

                                <a
                                    href="{{ route('chat.index', ['vendor' => $vendor->id]) }}"
                                    class="inline-flex items-center justify-center gap-2 px-5 h-11 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition-all whitespace-nowrap"
                                    data-chat-vendor-id="{{ $vendor->id }}"
                                >
                                    <i class="fa-solid fa-comments"></i>
                                    <span class="hidden sm:inline">Chat Vendor</span>
                                    <span class="sm:hidden">Chat</span>
                                </a>
                            </div>
                        @else
                            <a href="{{ route('chat.index') }}" class="inline-flex items-center justify-center gap-2 px-5 h-11 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition-all whitespace-nowrap">
                                <i class="fa-solid fa-comments"></i>
                                <span class="hidden sm:inline">Chat Vendor</span>
                                <span class="sm:hidden">Chat</span>
                            </a>
                        @endif
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="flex items-center justify-center w-11 h-11 rounded-xl border border-gray-300 text-gray-500 hover:border-red-300 hover:text-red-600 transition-all">
                                <i class="fa fa-heart text-lg"></i>
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 h-11 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition-all whitespace-nowrap">
                                <i class="fa-solid fa-comments"></i>
                                <span class="hidden sm:inline">Chat Vendor</span>
                                <span class="sm:hidden">Chat</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Vehicles -->
        <h2 class="text-xl font-bold text-gray-900 mb-4">Kendaraan ({{ $vendor->vehicles->count() }})</h2>
        
        @if($vendor->vehicles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vendor->vehicles as $vehicle)
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
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $vehicle->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ ucfirst($vehicle->category) }}
                                        @if($vehicle->engine_cc)
                                            • {{ $vehicle->engine_cc }}cc
                                        @endif
                                        • {{ $vehicle->year }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $vehicle->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-blue-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</span>
                                <div class="flex items-center gap-2">
                                    @auth
                                        @if(auth()->user()->role === 'user')
                                            <form action="{{ route('user.wishlist.vehicles.toggle', $vehicle) }}" method="POST" data-rn-wishlist>
                                                @csrf
                                                <button type="submit" class="btn-fav w-10 h-10 rounded-lg border {{ in_array($vehicle->id, $wishlistedVehicleIds ?? [], true) ? 'is-active border-red-300 text-red-600 bg-red-50' : 'border-gray-300 text-gray-500' }} hover:border-red-300 hover:text-red-600">
                                                    <i class="fa fa-heart"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="w-10 h-10 rounded-lg border border-gray-300 text-gray-500 hover:border-red-300 hover:text-red-600 flex items-center justify-center">
                                            <i class="fa fa-heart"></i>
                                        </a>
                                    @endauth
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500">Belum ada kendaraan yang terdaftar.</p>
            </div>
        @endif
    </section>
@endsection
