@extends('layouts.dashboard')

@section('title', __('Semua Kendaraan'))

@section('content')
@php $currentCategory = request('category'); @endphp

<x-dashboard.card padded="false">
    <x-slot name="title">{{ __('Daftar Kendaraan') }}</x-slot>
    <x-slot name="subtitle">{{ __('Semua kendaraan dari semua vendor terdaftar.') }}</x-slot>
    <x-slot name="actions">
        <form method="GET" action="{{ route('admin.vehicles.index') }}" class="flex items-center gap-2">
            <x-dashboard.select name="category" onchange="this.form.submit()" class="!h-10">
                <option value="">{{ __('Semua Kategori') }}</option>
                @foreach (\App\Models\Vehicle::CATEGORIES as $value => $label)
                    <option value="{{ $value }}" @selected($currentCategory === $value)>{{ $label }}</option>
                @endforeach
            </x-dashboard.select>
        </form>
    </x-slot>

    @if($vehicles->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Kendaraan') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Vendor') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Kategori') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Harga / Hari') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Booking') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($vehicles as $vehicle)
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-slate-100">
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
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="text-rn-text">{{ $vehicle->vendor?->store_name ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $vehicle->vendor?->district?->name ?? '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">
                                {{ \App\Models\Vehicle::CATEGORIES[$vehicle->category] ?? ucfirst($vehicle->category) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 font-semibold text-rn-text">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $vehicle->bookings->count() }}</td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <x-dashboard.badge :tone="$vehicle->status === 'available' ? 'success' : 'warning'">
                                    {{ $vehicle->status === 'available' ? __('Tersedia') : __('Tidak Tersedia') }}
                                </x-dashboard.badge>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-right">
                                <x-dashboard.btn variant="secondary" size="sm" :href="route('admin.vehicles.show', $vehicle)" icon="fa-eye">{{ __('Detail') }}</x-dashboard.btn>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-dashboard.empty icon="fa-motorcycle" message="{{ __('Tidak ada kendaraan ditemukan.') }}" />
    @endif
</x-dashboard.card>
@endsection
