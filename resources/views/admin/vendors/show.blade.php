@extends('layouts.dashboard')

@section('title', 'Detail Vendor')

@section('sidebar')
    <x-sidebar-link href="{{ route('admin.dashboard') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('admin.vendors.index') }}" :active="true">
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
    
    <x-sidebar-link href="{{ route('admin.bookings.index') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Booking
    </x-sidebar-link>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('admin.vendors.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $vendor->store_name }}</h2>
                    <p class="text-gray-500">{{ $vendor->district->name }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($vendor->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($vendor->status === 'approved') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($vendor->status) }}
                </span>
            </div>

            <!-- Owner Info -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-700 mb-3">Informasi Pemilik</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-semibold">{{ $vendor->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold">{{ $vendor->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Telepon</p>
                        <p class="font-semibold">{{ $vendor->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bergabung</p>
                        <p class="font-semibold">{{ $vendor->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="font-semibold text-gray-700 mb-3">Alamat</h3>
                <p class="text-gray-600">{{ $vendor->address }}</p>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $vendor->vehicles->count() }}</p>
                    <p class="text-sm text-blue-800">Kendaraan</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $vendor->vehicles->sum(fn($v) => $v->bookings->count()) }}</p>
                    <p class="text-sm text-green-800">Total Booking</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ $vendor->vehicles->where('status', 'available')->count() }}</p>
                    <p class="text-sm text-purple-800">Tersedia</p>
                </div>
            </div>

            <!-- Vehicles -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Daftar Kendaraan</h3>
                @if($vendor->vehicles->count() > 0)
                    <div class="space-y-2">
                        @foreach($vendor->vehicles as $vehicle)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $vehicle->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $vehicle->category }} • {{ $vehicle->year }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</p>
                                    <span class="text-xs {{ $vehicle->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $vehicle->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada kendaraan.</p>
                @endif
            </div>

            <!-- Actions -->
            @if($vendor->status === 'pending')
                <div class="flex space-x-4">
                    <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                            Approve Vendor
                        </button>
                    </form>
                    <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700"
                                onclick="return confirm('Yakin ingin menolak vendor ini?')">
                            Reject Vendor
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
