@extends('layouts.front')

@section('title', 'Wishlist Saya')

@section('content')
<section class="section booking-front-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Wishlist Saya</h2>
            <p class="booking-front-subtitle">Semua kendaraan dan vendor favoritmu tersimpan di sini.</p>
        </div>
        <a href="{{ route('search') }}" class="booking-btn-primary booking-inline-btn">Cari Kendaraan</a>
    </div>

    @if(session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-8">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Kendaraan Favorit</h3>
            @if($wishlistVehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($wishlistVehicles as $vehicle)
                        <article class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="h-44 bg-gray-100 flex items-center justify-center">
                                @if($vehicle->image)
                                    <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa fa-motorcycle text-4xl text-gray-400"></i>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900">{{ $vehicle->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $vehicle->vendor->store_name ?? '-' }} • {{ $vehicle->vendor->district->name ?? '-' }}</p>
                                <p class="text-blue-600 font-bold mt-3">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</p>

                                <div class="mt-4 flex items-center gap-2">
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="flex-1 text-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Lihat Detail</a>
                                    <form action="{{ route('user.wishlist.vehicles.toggle', $vehicle) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50" title="Hapus dari wishlist">
                                            <i class="fa fa-heart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="booking-empty-state">
                    <p>Belum ada kendaraan di wishlist kamu.</p>
                    <a href="{{ route('search') }}" class="booking-btn-secondary">Jelajahi Kendaraan</a>
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Vendor Favorit</h3>
            @if($wishlistVendors->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($wishlistVendors as $vendor)
                        <article class="bg-white rounded-lg border border-gray-200 p-4">
                            <h4 class="font-semibold text-gray-900">{{ $vendor->store_name }}</h4>
                            <p class="text-sm text-gray-500 mt-1">{{ $vendor->district->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $vendor->phone ?? '-' }}</p>

                            <div class="mt-4 flex items-center gap-2">
                                <a href="{{ route('vendors.show', $vendor) }}" class="flex-1 text-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Buka Vendor</a>
                                <form action="{{ route('user.wishlist.vendors.toggle', $vendor) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50" title="Hapus dari wishlist">
                                        <i class="fa fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="booking-empty-state">
                    <p>Belum ada vendor favorit di wishlist kamu.</p>
                    <a href="{{ route('search') }}" class="booking-btn-secondary">Cari Vendor</a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
