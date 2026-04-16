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
    @php
        $payoutMethods = [
            'BCA',
            'BRI',
            'BNI',
            'BSI',
            'Mandiri',
            'CIMB Niaga',
            'BTN',
            'Permata Bank',
            'Danamon',
            'OCBC NISP',
            'SeaBank',
            'DANA',
            'GoPay',
            'OVO',
            'LinkAja',
            'ShopeePay',
        ];

        $selectedPayoutMethod = old('bank_name', $vendor->bank_name ?? '');
    @endphp

    <div class="dash-card" style="max-width: 920px; margin: 0 auto;">
        <div class="dash-card-header">
            <h3 class="dash-card-title">
                {{ isset($vendor) && $vendor ? 'Ajukan Ulang Vendor' : 'Lengkapi Pendaftaran Vendor' }}
            </h3>
            <span class="dash-badge warning">Wajib isi data toko</span>
        </div>

        <div class="dash-card-body">
            @if (isset($vendor) && $vendor && $vendor->status === 'rejected')
                <div class="dash-alert dash-alert-error" style="margin-bottom: 16px;">
                    <strong>Pengajuan sebelumnya ditolak.</strong><br>
                    <span>{{ $vendor->rejection_reason ?: 'Silakan lengkapi kembali data dan dokumen Anda.' }}</span>
                </div>

                @if($vendor->documents->count() > 0)
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 mb-4">
                        <p class="text-sm font-semibold text-red-700 mb-3">Catatan Review Per Dokumen</p>

                        <div class="space-y-3">
                            @foreach($vendor->documents as $document)
                                <div class="rounded-lg border border-red-100 bg-white p-3">
                                    <div class="flex items-start justify-between gap-3 mb-1">
                                        <p class="text-sm font-semibold text-slate-800">{{ strtoupper($document->type) }}</p>
                                        <span class="text-xs px-2 py-1 rounded-full
                                            @if($document->status === 'approved') bg-green-100 text-green-700
                                            @elseif($document->status === 'rejected') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            {{ ucfirst($document->status) }}
                                        </span>
                                    </div>

                                    <p class="text-xs text-slate-500 mb-2">{{ $document->created_at->diffForHumans() }}</p>

                                    @if($document->notes)
                                        <p class="text-sm text-red-700">{{ $document->notes }}</p>
                                    @else
                                        <p class="text-sm text-slate-500">Belum ada catatan tambahan dari admin untuk dokumen ini.</p>
                                    @endif

                                    <a href="{{ route('documents.vendor.media', $document) }}" target="_blank" class="inline-block mt-2 text-sm text-blue-600 hover:underline">Lihat Dokumen</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

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
                    <input type="text" name="store_name" value="{{ old('store_name', $vendor->store_name ?? '') }}" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Renmote Rental Motor Malang">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $vendor->phone ?? auth()->user()->phone_number) }}" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                    <select name="district_id" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" @selected(old('district_id', $vendor->district_id ?? null) == $district->id)>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Toko</label>
                    <textarea name="address" rows="3" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Alamat lengkap toko">{{ old('address', $vendor->address ?? '') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Toko</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Ceritakan singkat tentang toko Anda">{{ old('description', $vendor->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Metode Pencairan (Bank / E-Wallet)</label>
                    <select name="bank_name" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih metode pencairan</option>
                        @foreach($payoutMethods as $method)
                            <option value="{{ $method }}" @selected($selectedPayoutMethod === $method)>{{ $method }}</option>
                        @endforeach
                        @if($selectedPayoutMethod !== '' && !in_array($selectedPayoutMethod, $payoutMethods, true))
                            <option value="{{ $selectedPayoutMethod }}" selected>{{ $selectedPayoutMethod }}</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Rekening / Nomor E-Wallet</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $vendor->bank_account ?? '') }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 6037006574 atau 08xxxxxxxxxx">
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
                        {{ isset($vendor) && $vendor ? 'Ajukan Ulang Vendor' : 'Kirim Pendaftaran Vendor' }}
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                        Nanti Saja
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
