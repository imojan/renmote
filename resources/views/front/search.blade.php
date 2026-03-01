@extends('layouts.front')

@section('title', 'Cari Motor')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Cari Motor</h1>
        
        <!-- Search Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form action="{{ route('search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <select name="district_id" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Lokasi</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        <option value="Matic" {{ request('category') == 'Matic' ? 'selected' : '' }}>Matic</option>
                        <option value="Sport" {{ request('category') == 'Sport' ? 'selected' : '' }}>Sport</option>
                        <option value="Bebek" {{ request('category') == 'Bebek' ? 'selected' : '' }}>Bebek</option>
                        <option value="Trail" {{ request('category') == 'Trail' ? 'selected' : '' }}>Trail</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="md:col-span-4">
                    <button type="submit" class="w-full md:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Cari Motor
                    </button>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="mb-4">
            <p class="text-gray-600">Ditemukan {{ $vehicles->count() }} motor</p>
        </div>
        
        @if($vehicles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $vehicle->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $vehicle->category }} • {{ $vehicle->year }}</p>
                                </div>
                                @if($vehicle->vendor->verified)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Verified</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('vendors.show', $vehicle->vendor) }}" class="text-sm text-blue-600 hover:underline">
                                {{ $vehicle->vendor->store_name }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $vehicle->vendor->district->name }}</p>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-blue-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</span>
                                <a href="{{ route('vehicles.show', $vehicle) }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500">Tidak ada motor yang ditemukan dengan filter tersebut.</p>
            </div>
        @endif
    </div>
@endsection
