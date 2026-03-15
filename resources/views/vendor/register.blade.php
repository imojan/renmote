@extends('layouts.dashboard')

@section('title', 'Daftar Vendor')

@section('sidebar')
    <x-sidebar-link href="{{ route('user.dashboard') }}" :active="false">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('vendor.register') }}" :active="true">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Lengkapi Data Vendor
    </x-sidebar-link>
@endsection

@section('content')
    <div class="dash-card" style="max-width: 920px; margin: 0 auto;">
        <div class="dash-card-header">
            <h3 class="dash-card-title">Lengkapi Pendaftaran Vendor</h3>
            <span class="dash-badge warning">Wajib isi data toko</span>
        </div>

        <div class="dash-card-body">
            @if (session('error'))
                <div class="dash-alert dash-alert-error" style="margin-bottom: 16px;">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="dash-alert dash-alert-error" style="margin-bottom: 16px;">
                    <div>
                        <strong>Form belum lengkap:</strong>
                        <ul style="margin: 8px 0 0 16px; list-style: disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('vendor.register.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="store_name" value="{{ old('store_name') }}" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Renmote Rental Jogja">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone_number) }}" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                    <select name="district_id" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Toko</label>
                    <textarea name="address" rows="3" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Alamat lengkap toko">{{ old('address') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Toko</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Ceritakan singkat tentang toko Anda">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="BCA / BRI / Mandiri">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Rekening</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Nomor rekening">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Upload KTP <span class="text-red-500">*</span></label>
                    <input type="file" name="ktp" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-sm text-slate-700">
                    <p class="text-xs text-slate-500 mt-1">Format: JPG/PNG/PDF, max 2MB</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Upload Surat Izin</label>
                    <input type="file" name="permit" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-slate-700">
                    <p class="text-xs text-slate-500 mt-1">Format: JPG/PNG/PDF, max 2MB</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Upload Foto Toko</label>
                    <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="w-full text-sm text-slate-700">
                    <p class="text-xs text-slate-500 mt-1">Format: JPG/PNG, max 2MB</p>
                </div>

                <div class="md:col-span-2 flex items-center gap-3 pt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors">
                        Kirim Pendaftaran Vendor
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                        Nanti Saja
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
