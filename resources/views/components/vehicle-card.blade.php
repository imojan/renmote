@props(['vehicle'])

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
        <h3 class="font-semibold text-gray-900">{{ $vehicle->name }}</h3>
        <p class="text-sm text-gray-500">{{ $vehicle->vendor->store_name }} • {{ $vehicle->vendor->district->name }}</p>
        <p class="text-sm text-gray-500">{{ ucfirst($vehicle->category) }} • {{ $vehicle->year }}</p>
        <div class="mt-3 flex items-center justify-between">
            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</span>
            <a href="{{ route('vehicles.show', $vehicle) }}" class="text-sm text-blue-600 hover:underline">Detail</a>
        </div>
    </div>
</div>
