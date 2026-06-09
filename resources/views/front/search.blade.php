@extends('layouts.front')

@section('title', 'Cari Motor')

@section('content')
    <section class="section front-content-section front-search-section">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Cari Motor</h1>
        
        <!-- Search Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            @php($activeCategorySlug = $selectedCategorySlug ?? (request()->filled('category') ? \Illuminate\Support\Str::of((string) request('category'))->lower()->replace('_', '-')->replace(' ', '-')->slug('-')->toString() : null))
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
                        <option value="matic" {{ $activeCategorySlug === 'matic' ? 'selected' : '' }}>Matic</option>
                        <option value="bebek" {{ $activeCategorySlug === 'bebek' ? 'selected' : '' }}>Bebek</option>
                        <option value="sport" {{ $activeCategorySlug === 'sport' ? 'selected' : '' }}>Sport</option>
                        <option value="trail" {{ $activeCategorySlug === 'trail' ? 'selected' : '' }}>Trail</option>
                        <option value="skutik_premium" {{ $activeCategorySlug === 'skutik-premium' || $activeCategorySlug === 'skutik_premium' ? 'selected' : '' }}>Skutik Premium</option>
                        <option value="bigbike" {{ $activeCategorySlug === 'bigbike' ? 'selected' : '' }}>Bigbike</option>
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
            <p class="text-gray-600">{{ __('Ditemukan') }} {{ $vehicles->total() }} {{ __('motor') }}</p>
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
                                    <p class="text-sm text-gray-500">{{ $vehicle->category_label }} • {{ $vehicle->year }} • {{ $vehicle->engine_cc ? $vehicle->engine_cc . 'cc' : '-' }}</p>
                                </div>
                                @if($vehicle->vendor->verified)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full inline-flex items-center gap-1 flex-shrink-0">
                                        <i class="fa fa-check-circle"></i> Verified
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-3 mt-4 mb-2">
                                <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 border border-gray-100 bg-white shadow-sm flex items-center justify-center">
                                    @if($vehicle->vendor->profile_photo)
                                        <img src="{{ Storage::url($vehicle->vendor->profile_photo) }}" alt="{{ $vehicle->vendor->store_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-red-100 flex items-center justify-center text-[11px] font-bold text-red-600">
                                            {{ strtoupper(substr($vehicle->vendor->store_name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('vendors.show', $vehicle->vendor) }}" class="text-sm text-blue-600 hover:underline font-semibold block truncate">
                                        {{ $vehicle->vendor->store_name }}
                                    </a>
                                    <p class="text-xs text-gray-500 truncate mt-0.5"><i class="fa fa-map-marker-alt text-[10px] mr-1 text-gray-400"></i>{{ $vehicle->vendor->district->name }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between gap-2">
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
                                    Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($vehicles->hasPages())
                <div class="mt-8">{{ $vehicles->links() }}</div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500">Tidak ada motor yang ditemukan dengan filter tersebut.</p>
            </div>
        @endif
    </section>
@endsection
