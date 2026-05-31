@extends('layouts.dashboard')

@section('title', __('Kelola Pesanan'))

@section('content')
@php
    $currentStatus = request('status');
    $currentSortBy = $sortBy ?? request('sort_by', 'id');
    $currentSortDir = $sortDir ?? request('sort_dir', 'desc');
    $nextSortDir = $currentSortDir === 'asc' ? 'desc' : 'asc';

    $statusOptions = [
        ''          => __('Semua'),
        'pending'   => __('Pending'),
        'confirmed' => __('Confirmed'),
        'declined'  => __('Declined'),
        'completed' => __('Completed'),
    ];

    $sortOptions = [
        'id'             => __('ID'),
        'customer_name'  => __('Pelanggan'),
        'vehicle_name'   => __('Kendaraan'),
        'booking_date'   => __('Tanggal'),
        'total_paid'     => __('Total'),
    ];
@endphp

<x-dashboard.card padded="false">
    <x-slot name="title">{{ __('Daftar Pesanan') }}</x-slot>
    <x-slot name="actions">
        <x-dashboard.btn variant="secondary" icon="fa-store" :href="route('vendor.bookings.manual.create')">
            {{ __('Catat Booking Offline') }}
        </x-dashboard.btn>
        <x-dashboard.btn variant="primary" icon="fa-file-excel"
                         :href="route('vendor.bookings.export', request()->only(['status', 'sort_by', 'sort_dir']))">
            Export XLSX
        </x-dashboard.btn>
    </x-slot>

    <div class="border-b border-slate-100 p-4 sm:p-6">
        <form action="{{ route('vendor.bookings.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4">
                <x-dashboard.field label="{{ __('Status') }}" for="status">
                    <x-dashboard.select id="status" name="status">
                        @foreach($statusOptions as $val => $label)
                            <option value="{{ $val }}" @selected(($currentStatus ?? '') === $val)>{{ $label }}</option>
                        @endforeach
                    </x-dashboard.select>
                </x-dashboard.field>

                <x-dashboard.field label="{{ __('Urut Berdasarkan') }}" for="sort_by">
                    <x-dashboard.select id="sort_by" name="sort_by">
                        @foreach($sortOptions as $key => $label)
                            <option value="{{ $key }}" @selected($currentSortBy === $key)>{{ $label }}</option>
                        @endforeach
                    </x-dashboard.select>
                </x-dashboard.field>

                <input type="hidden" name="sort_dir" value="{{ $currentSortDir }}">

                <x-dashboard.field label="{{ __('Aksi Filter') }}">
                    <x-dashboard.btn variant="primary" type="submit" icon="fa-filter" class="w-full">
                        {{ __('Terapkan') }}
                    </x-dashboard.btn>
                </x-dashboard.field>

                <x-dashboard.field label="{{ __('Urutan') }}">
                    <a href="{{ route('vendor.bookings.index', array_filter([
                            'status' => $currentStatus,
                            'sort_by' => $currentSortBy,
                            'sort_dir' => $nextSortDir,
                        ], fn ($value) => $value !== null && $value !== '')) }}"
                       class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-semibold text-rn-text transition hover:border-rn-primary/40 hover:text-rn-primary">
                        <i class="fa-solid {{ $currentSortDir === 'asc' ? 'fa-arrow-up-wide-short' : 'fa-arrow-down-wide-short' }}"></i>
                        {{ $currentSortDir === 'asc' ? __('Ascending') : __('Descending') }}
                    </a>
                </x-dashboard.field>
            </div>
        </form>
    </div>

    @if($bookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">ID</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Pelanggan') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Kendaraan') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Tanggal') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Total') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($bookings as $booking)
                        @php
                            $tone = match ($booking->status) {
                                'confirmed' => 'success',
                                'completed' => 'info',
                                'cancelled' => 'danger',
                                default => 'warning',
                            };
                            $statusLabel = $booking->status === 'cancelled' ? __('Declined') : ucfirst($booking->status);
                        @endphp
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-3 text-slate-500">#{{ $booking->id }}</td>
                            <td class="whitespace-nowrap px-6 py-3">
                                @if(($booking->source ?? 'online') === 'walk_in')
                                    <div class="flex items-center gap-2">
                                        <div class="font-semibold text-rn-text">
                                            {{ $booking->customer_name ?: __('Walk-in tanpa nama') }}
                                        </div>
                                        <x-dashboard.badge tone="primary" class="text-[10px]">
                                            <i class="fa fa-store text-[9px]"></i> Walk-in
                                        </x-dashboard.badge>
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $booking->customer_phone ?: __('No. HP tidak dicatat') }}</div>
                                @else
                                    <div class="font-semibold text-rn-text">{{ $booking->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->user->email ?? '-' }}</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-slate-700">{{ $booking->vehicle->name ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-3 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($booking->start_date)->locale(app()->getLocale())->translatedFormat('d M') }} -
                                {{ \Carbon\Carbon::parse($booking->end_date)->locale(app()->getLocale())->translatedFormat('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 font-semibold text-rn-text">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <x-dashboard.badge :tone="$tone">{{ $statusLabel }}</x-dashboard.badge>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-right">
                                <div class="inline-flex flex-wrap items-center justify-end gap-2">
                                    <x-dashboard.btn variant="secondary" size="sm" :href="route('vendor.bookings.show', $booking)">{{ __('Detail') }}</x-dashboard.btn>
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('vendor.bookings.confirm', $booking) }}" method="POST" class="inline"
                                              data-confirm-title="{{ __('Konfirmasi pesanan?') }}"
                                              data-confirm-message="{{ __('Pesanan ini akan dikonfirmasi.') }}"
                                              data-confirm-confirm-text="{{ __('Ya, Konfirmasi') }}"
                                              data-confirm-cancel-text="{{ __('Batal') }}">
                                            @csrf
                                            <x-dashboard.btn variant="primary" size="sm" type="submit" icon="fa-check">{{ __('Konfirmasi') }}</x-dashboard.btn>
                                        </form>
                                        <form action="{{ route('vendor.bookings.reject', $booking) }}" method="POST" class="inline"
                                              data-confirm-title="{{ __('Tolak pesanan?') }}"
                                              data-confirm-message="{{ __('Pesanan ini akan ditolak. Lanjutkan?') }}"
                                              data-confirm-confirm-text="{{ __('Ya, Tolak') }}"
                                              data-confirm-cancel-text="{{ __('Batal') }}">
                                            @csrf
                                            <x-dashboard.btn variant="danger" size="sm" type="submit" icon="fa-xmark">{{ __('Tolak') }}</x-dashboard.btn>
                                        </form>
                                    @endif
                                    @if($booking->status === 'confirmed')
                                        <form action="{{ route('vendor.bookings.complete', $booking) }}" method="POST" class="inline"
                                              data-confirm-title="{{ __('Selesaikan pesanan?') }}"
                                              data-confirm-message="{{ __('Tandai pesanan sebagai selesai. Lanjutkan?') }}"
                                              data-confirm-confirm-text="{{ __('Ya, Selesai') }}"
                                              data-confirm-cancel-text="{{ __('Batal') }}">
                                            @csrf
                                            <x-dashboard.btn variant="primary" size="sm" type="submit" icon="fa-flag-checkered">{{ __('Selesai') }}</x-dashboard.btn>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-dashboard.empty icon="fa-clipboard-list" message="{{ __('Belum ada pesanan.') }}" />
    @endif
</x-dashboard.card>
@endsection
