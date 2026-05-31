@extends('layouts.dashboard')

@section('title', __('Tambah Kendaraan'))

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('vendor.vehicles.index') }}"
       class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-rn-primary hover:underline">
        <i class="fa fa-arrow-left text-xs"></i> {{ __('Kembali') }}
    </a>

    <x-dashboard.card title="{{ __('Tambah Kendaraan Baru') }}"
                      subtitle="{{ __('Lengkapi data kendaraan supaya muncul di katalog Renmote.') }}">
        <form action="{{ route('vendor.vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <x-dashboard.field label="{{ __('Nama Kendaraan') }}" for="name" :error="$errors->first('name')">
                <x-dashboard.input id="name" name="name" :value="old('name')" required placeholder="Honda Beat 2023" />
            </x-dashboard.field>

            <div class="grid gap-4 md:grid-cols-3">
                <x-dashboard.field label="{{ __('Kategori') }}" for="category" :error="$errors->first('category')">
                    <x-dashboard.select id="category" name="category" required>
                        <option value="">{{ __('Pilih Kategori') }}</option>
                        @foreach (\App\Models\Vehicle::CATEGORIES as $value => $label)
                            <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                        @endforeach
                    </x-dashboard.select>
                </x-dashboard.field>

                <x-dashboard.field label="{{ __('CC Mesin') }}" for="engine_cc" :error="$errors->first('engine_cc')">
                    <x-dashboard.input id="engine_cc" type="number" name="engine_cc" :value="old('engine_cc')" required min="50" max="3000" placeholder="125" />
                </x-dashboard.field>

                <x-dashboard.field label="{{ __('Tahun') }}" for="year" :error="$errors->first('year')">
                    <x-dashboard.input id="year" type="number" name="year" :value="old('year', date('Y'))" required min="2000" max="{{ date('Y') + 1 }}" />
                </x-dashboard.field>
            </div>

            <x-dashboard.field label="{{ __('Deskripsi') }}" for="description" :error="$errors->first('description')">
                <textarea id="description" name="description" rows="3"
                          class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-rn-text placeholder:text-slate-400 transition focus:border-rn-primary focus:outline-none focus:ring-2 focus:ring-rn-primary/15"
                          placeholder="{{ __('Deskripsi kendaraan...') }}">{{ old('description') }}</textarea>
            </x-dashboard.field>

            <div class="grid gap-4 md:grid-cols-2">
                <x-dashboard.field label="{{ __('Harga per Hari (Rp)') }}" for="price_per_day" :error="$errors->first('price_per_day')">
                    <x-dashboard.input id="price_per_day" type="number" name="price_per_day" :value="old('price_per_day')" required min="0" placeholder="75000" />
                </x-dashboard.field>

                <x-dashboard.field label="{{ __('Stok / Jumlah Unit') }}" for="stock" :error="$errors->first('stock')">
                    <x-dashboard.input id="stock" type="number" name="stock" :value="old('stock', 1)" required min="1" />
                </x-dashboard.field>
            </div>

            <x-dashboard.field label="{{ __('Foto Kendaraan') }}" for="image" :error="$errors->first('image')">
                <input id="image" type="file" name="image" accept="image/*"
                       class="block w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-rn-primary/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-rn-primary">
            </x-dashboard.field>

            <x-dashboard.field label="{{ __('Status') }}" for="status">
                <x-dashboard.select id="status" name="status">
                    <option value="available" @selected(old('status', 'available') === 'available')>{{ __('Tersedia') }}</option>
                    <option value="unavailable" @selected(old('status') === 'unavailable')>{{ __('Tidak Tersedia') }}</option>
                </x-dashboard.select>
            </x-dashboard.field>

            <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
                <x-dashboard.btn variant="primary" icon="fa-floppy-disk" type="submit">{{ __('Simpan Kendaraan') }}</x-dashboard.btn>
                <x-dashboard.btn variant="secondary" :href="route('vendor.vehicles.index')">{{ __('Batal') }}</x-dashboard.btn>
            </div>
        </form>
    </x-dashboard.card>
</div>
@endsection
