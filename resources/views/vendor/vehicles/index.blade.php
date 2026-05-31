@extends('layouts.dashboard')

@section('title', __('Kelola Kendaraan'))

@section('content')
<x-dashboard.card padded="false">
    <x-slot name="actions">
        <x-dashboard.btn variant="primary" icon="fa-plus" :href="route('vendor.vehicles.create')">{{ __('Tambah Kendaraan') }}</x-dashboard.btn>
    </x-slot>

    @if($vehicles->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Kendaraan') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Kategori') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Harga / Hari') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Stok') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($vehicles as $vehicle)
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                        @if($vehicle->image)
                                            <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-slate-400"><i class="fa fa-motorcycle"></i></div>
                                        @endif
                                    </div>
                                    <div class="leading-tight">
                                        <div class="font-semibold text-rn-text">{{ $vehicle->name }}</div>
                                        <div class="text-xs text-slate-500">
                                            @if($vehicle->engine_cc) {{ $vehicle->engine_cc }}cc · @endif {{ $vehicle->year }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">
                                {{ \App\Models\Vehicle::CATEGORIES[$vehicle->category] ?? ucfirst($vehicle->category) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 font-semibold text-rn-text">
                                Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $vehicle->stock }}</td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <x-dashboard.badge :tone="$vehicle->status === 'available' ? 'success' : 'warning'">
                                    {{ $vehicle->status === 'available' ? __('Tersedia') : __('Tidak Tersedia') }}
                                </x-dashboard.badge>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <x-dashboard.btn variant="secondary" size="sm" :href="route('vendor.vehicles.edit', $vehicle)" icon="fa-pen">{{ __('Edit') }}</x-dashboard.btn>
                                    <form action="{{ route('vendor.vehicles.destroy', $vehicle) }}" method="POST" class="inline"
                                          data-confirm-title="{{ __('Hapus kendaraan?') }}"
                                          data-confirm-message="{{ __('Data kendaraan ini akan dihapus permanen.') }}"
                                          data-confirm-confirm-text="{{ __('Ya, Hapus') }}"
                                          data-confirm-cancel-text="{{ __('Batal') }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dashboard.btn variant="danger" size="sm" icon="fa-trash" type="submit">{{ __('Hapus') }}</x-dashboard.btn>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-dashboard.empty icon="fa-motorcycle">
            {{ __('Belum ada kendaraan.') }}
            <a href="{{ route('vendor.vehicles.create') }}" class="font-semibold text-rn-primary hover:underline">{{ __('Tambah sekarang') }}</a>
        </x-dashboard.empty>
    @endif
</x-dashboard.card>
@endsection
