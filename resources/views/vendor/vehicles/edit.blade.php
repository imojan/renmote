@extends('layouts.dashboard')

@section('title', 'Edit Kendaraan')

@section('sidebar')
    <x-sidebar-link href="{{ route('vendor.dashboard') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('vendor.vehicles.index') }}" :active="true">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Kendaraan
    </x-sidebar-link>
    
    <x-sidebar-link href="{{ route('vendor.bookings.index') }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Pesanan
    </x-sidebar-link>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('vendor.vehicles.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Kendaraan</h2>

            <form action="{{ route('vendor.vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kendaraan</label>
                        <input type="text" name="name" value="{{ old('name', $vehicle->name) }}" required
                               class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                                <option value="matic" {{ old('category', $vehicle->category) == 'matic' ? 'selected' : '' }}>Matic</option>
                                <option value="manual" {{ old('category', $vehicle->category) == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="sport" {{ old('category', $vehicle->category) == 'sport' ? 'selected' : '' }}>Sport</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" required min="2000" max="{{ date('Y') + 1 }}"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-500 @enderror">
                            @error('year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3"
                                  class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $vehicle->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Hari (Rp)</label>
                            <input type="number" name="price_per_day" value="{{ old('price_per_day', $vehicle->price_per_day) }}" required min="0"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('price_per_day') border-red-500 @enderror">
                            @error('price_per_day')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok / Jumlah Unit</label>
                            <input type="number" name="stock" value="{{ old('stock', $vehicle->stock) }}" required min="1"
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror">
                            @error('stock')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Kendaraan</label>
                        @if($vehicle->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                               class="w-full border border-gray-300 rounded-lg p-2 @error('image') border-red-500 @enderror">
                        <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <select name="status" class="border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="available" {{ old('status', $vehicle->status) === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="unavailable" {{ old('status', $vehicle->status) === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        Update Kendaraan
                    </button>
                    <a href="{{ route('vendor.vehicles.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
