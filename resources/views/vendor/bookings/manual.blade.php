@extends('layouts.dashboard')

@section('title', __('Catat Booking Offline'))

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('vendor.bookings.index') }}"
       class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-rn-primary hover:underline">
        <i class="fa fa-arrow-left text-xs"></i> {{ __('Kembali') }}
    </a>

    <x-dashboard.card title="{{ __('Catat Booking Walk-in') }}"
                      subtitle="{{ __('Gunakan form ini saat ada penyewa datang langsung ke tempat. Tanggal yang dicatat akan otomatis terblokir di pencarian online.') }}">

        @if($vehicles->isEmpty())
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                {{ __('Belum ada kendaraan dengan status "Tersedia". Tambah kendaraan dulu lewat menu Kendaraan.') }}
            </div>
        @else
            <form action="{{ route('vendor.bookings.manual.store') }}" method="POST" class="space-y-5">
                @csrf

                <x-dashboard.field label="{{ __('Pilih Kendaraan') }}" for="vehicle_id" :error="$errors->first('vehicle_id')">
                    <x-dashboard.select id="vehicle_id" name="vehicle_id" required>
                        <option value="">{{ __('-- Pilih kendaraan --') }}</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
                                {{ $vehicle->name }} ({{ $vehicle->year }}) — Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari
                            </option>
                        @endforeach
                    </x-dashboard.select>
                </x-dashboard.field>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-dashboard.field label="{{ __('Tanggal Mulai') }}" for="start_date" :error="$errors->first('start_date')">
                        <x-dashboard.input id="start_date" type="date" name="start_date"
                                           :value="old('start_date', date('Y-m-d'))" required min="{{ date('Y-m-d') }}" />
                    </x-dashboard.field>

                    <x-dashboard.field label="{{ __('Tanggal Selesai') }}" for="end_date" :error="$errors->first('end_date')">
                        <x-dashboard.input id="end_date" type="date" name="end_date"
                                           :value="old('end_date')" required min="{{ date('Y-m-d') }}" />
                    </x-dashboard.field>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Data Penyewa (opsional)') }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Hanya untuk catatan internal vendor.') }}</p>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <x-dashboard.field label="{{ __('Nama Penyewa') }}" for="customer_name" :error="$errors->first('customer_name')">
                            <x-dashboard.input id="customer_name" name="customer_name" :value="old('customer_name')"
                                               placeholder="{{ __('Nama lengkap penyewa') }}" />
                        </x-dashboard.field>

                        <x-dashboard.field label="{{ __('No. HP Penyewa') }}" for="customer_phone" :error="$errors->first('customer_phone')">
                            <x-dashboard.input id="customer_phone" name="customer_phone" :value="old('customer_phone')"
                                               placeholder="08xxxxxxxxxx" />
                        </x-dashboard.field>
                    </div>

                    <x-dashboard.field label="{{ __('Catatan') }}" for="walkin_notes" class="mt-4" :error="$errors->first('walkin_notes')">
                        <textarea id="walkin_notes" name="walkin_notes" rows="3" maxlength="500"
                                  class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-rn-text placeholder:text-slate-400 transition focus:border-rn-primary focus:outline-none focus:ring-2 focus:ring-rn-primary/15"
                                  placeholder="{{ __('Misal: bayar cash di tempat, sudah serah terima jam 10.00, dll.') }}">{{ old('walkin_notes') }}</textarea>
                    </x-dashboard.field>
                </div>

                <div class="rounded-xl border border-rn-primary/20 bg-rn-primary/5 p-4 text-sm text-rn-primary">
                    <i class="fa fa-circle-info mr-1.5"></i>
                    {{ __('Booking ini akan langsung berstatus Confirmed sehingga tanggal terkunci di sistem dan tidak bisa dibooking ulang oleh penyewa online.') }}
                </div>

                <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
                    <x-dashboard.btn variant="primary" icon="fa-floppy-disk" type="submit">
                        {{ __('Simpan Booking Offline') }}
                    </x-dashboard.btn>
                    <x-dashboard.btn variant="secondary" :href="route('vendor.bookings.index')">{{ __('Batal') }}</x-dashboard.btn>
                </div>
            </form>
        @endif
    </x-dashboard.card>
</div>
@endsection
