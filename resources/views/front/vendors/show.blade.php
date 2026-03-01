@extends('layouts.front')

@section('title', $vendor->store_name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Vendor Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center">
                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mr-6">
                    <span class="text-3xl font-bold text-gray-600">{{ substr($vendor->store_name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $vendor->store_name }}
                        @if($vendor->verified)
                            <span class="ml-2 px-2 py-1 text-sm bg-green-100 text-green-800 rounded-full">✓ Terverifikasi</span>
                        @endif
                    </h1>
                    <p class="text-gray-500">{{ $vendor->district->name }}</p>
                    @if($vendor->description)
                        <p class="text-gray-600 mt-2">{{ $vendor->description }}</p>
                    @endif
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
                                    <p class="text-sm text-gray-500">{{ $vehicle->category }} • {{ $vehicle->year }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $vehicle->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-blue-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</span>
                                <a href="{{ route('vehicles.show', $vehicle) }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                    Detail
                                </a>
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
    </div>
@endsection
