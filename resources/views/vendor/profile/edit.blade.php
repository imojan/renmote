@extends('layouts.dashboard')

@section('title', __('Pengaturan Profil Vendor'))

@section('content')
@php
    $docTypes = [
        'ktp'    => __('KTP Pemilik'),
        'permit' => __('Surat Izin Usaha'),
        'photo'  => __('Foto Toko / Lokasi'),
    ];
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Hero card with cover & avatar --}}
    <div class="lg:col-span-3" data-rn-reveal>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="relative h-44 sm:h-56 bg-gradient-to-br from-rn-primary/10 via-white to-rn-accent/10">
                @if($vendor->cover_photo)
                    <img src="{{ Storage::url($vendor->cover_photo) }}" alt="" class="h-full w-full object-cover">
                @endif
                <div class="absolute right-4 top-4 flex flex-wrap items-center gap-2">
                    <form action="{{ route('vendor.profile.cover.update') }}" method="POST" enctype="multipart/form-data" id="coverUploadForm" class="hidden">
                        @csrf
                        <input type="file" name="cover_photo" id="coverFileInput" accept="image/*" onchange="document.getElementById('coverUploadForm').submit()">
                    </form>
                    <button type="button"
                            onclick="document.getElementById('coverFileInput').click()"
                            class="inline-flex h-9 items-center gap-2 rounded-full bg-white/90 px-4 text-xs font-semibold text-rn-text shadow-sm backdrop-blur transition hover:bg-white">
                        <i class="fa fa-camera"></i> {{ $vendor->cover_photo ? __('Ganti Cover') : __('Upload Cover') }}
                    </button>
                    @if($vendor->cover_photo)
                        <form action="{{ route('vendor.profile.cover.destroy') }}" method="POST"
                              data-confirm-title="{{ __('Hapus foto cover?') }}"
                              data-confirm-message="{{ __('Foto background toko akan dihapus.') }}"
                              data-confirm-confirm-text="{{ __('Ya, Hapus') }}"
                              data-confirm-cancel-text="{{ __('Batal') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex h-9 items-center gap-2 rounded-full bg-red-500/90 px-4 text-xs font-semibold text-white shadow-sm transition hover:bg-red-600">
                                <i class="fa fa-trash"></i> {{ __('Hapus') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="relative flex flex-col items-start gap-4 px-6 pb-6 sm:flex-row sm:items-end sm:justify-between sm:px-8 pt-4">
                <div class="flex items-end gap-4">
                    <div class="h-24 w-24 shrink-0 overflow-hidden rounded-2xl border-4 border-white bg-slate-200 shadow-md sm:h-28 sm:w-28 {{ $vendor->cover_photo ? '-mt-12 sm:-mt-16' : '' }}">
                        @if($vendor->profile_photo)
                            <img src="{{ Storage::url($vendor->profile_photo) }}" alt="{{ $vendor->store_name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-3xl text-slate-400">
                                <i class="fa fa-store"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-rn-text sm:text-2xl">{{ $vendor->store_name }}</h2>
                        <p class="text-sm text-slate-500"><i class="fa fa-map-marker-alt text-xs"></i> {{ $vendor->district?->name ?? __('Belum diatur') }}</p>
                        @if($vendor->rating)
                            <p class="mt-1 text-sm font-semibold text-rn-text">
                                <i class="fa fa-star text-amber-400"></i>
                                {{ number_format($vendor->rating, 1) }}
                                <span class="text-xs text-slate-500">({{ number_format($vendor->rating_count ?? 0) }} review)</span>
                            </p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('vendors.show', $vendor) }}" target="_blank"
                   class="inline-flex h-9 items-center gap-2 rounded-full border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 transition hover:border-rn-primary/40 hover:text-rn-primary">
                    <i class="fa fa-arrow-up-right-from-square text-[10px]"></i>
                    {{ __('Lihat Halaman Publik') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Profile bisnis --}}
    <div class="lg:col-span-2 space-y-6">
        <x-dashboard.card title="{{ __('Profil Bisnis') }}" subtitle="{{ __('Data utama yang ditampilkan di topbar dashboard dan jadi kontak admin Renmote.') }}" data-rn-reveal>
            <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PATCH')
                <x-dashboard.field label="{{ __('Foto Profil Bisnis') }}" for="profile_photo" hint="{{ __('JPG/PNG, maks 2MB. Tampil sebagai avatar di topbar.') }}">
                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*"
                           class="block w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-rn-primary/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-rn-primary">
                </x-dashboard.field>
                <div class="grid gap-4 md:grid-cols-2">
                    <x-dashboard.field label="{{ __('Nama Pemilik / Admin Vendor') }}" for="name" :error="$errors->first('name')">
                        <x-dashboard.input id="name" name="name" :value="old('name', $user->name)" required />
                    </x-dashboard.field>
                    <x-dashboard.field label="{{ __('Email Aktif') }}" for="email" :error="$errors->first('email')">
                        <x-dashboard.input id="email" type="email" name="email" :value="old('email', $user->email)" required />
                    </x-dashboard.field>
                </div>
                <x-dashboard.field label="{{ __('Nomor HP / WhatsApp') }}" for="phone_number" :error="$errors->first('phone_number')">
                    <x-dashboard.input id="phone_number" name="phone_number" :value="old('phone_number', $user->phone_number)" placeholder="08xxxxxxxxxx" />
                </x-dashboard.field>
                <div class="border-t border-slate-100 pt-4">
                    <x-dashboard.btn variant="primary" icon="fa-floppy-disk" type="submit">{{ __('Simpan Profil') }}</x-dashboard.btn>
                </div>
            </form>
        </x-dashboard.card>

        {{-- Info Toko --}}
        <x-dashboard.card title="{{ __('Informasi Toko') }}" subtitle="{{ __('Nama toko, deskripsi, area, dan alamat lengkap.') }}" data-rn-reveal>
            <form action="{{ route('vendor.profile.store.update') }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <x-dashboard.field label="{{ __('Nama Toko') }}" for="store_name" :error="$errors->first('store_name')">
                    <x-dashboard.input id="store_name" name="store_name" :value="old('store_name', $vendor->store_name)" required />
                </x-dashboard.field>
                <x-dashboard.field label="{{ __('Deskripsi Singkat') }}" for="description" :error="$errors->first('description')">
                    <textarea id="description" name="description" rows="3" maxlength="1000"
                              class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-rn-text placeholder:text-slate-400 transition focus:border-rn-primary focus:outline-none focus:ring-2 focus:ring-rn-primary/15">{{ old('description', $vendor->description) }}</textarea>
                </x-dashboard.field>
                <div class="grid gap-4 md:grid-cols-2">
                    <x-dashboard.field label="{{ __('Telepon Toko') }}" for="phone" :error="$errors->first('phone')">
                        <x-dashboard.input id="phone" name="phone" :value="old('phone', $vendor->phone)" />
                    </x-dashboard.field>
                    <x-dashboard.field label="{{ __('Kecamatan') }}" for="district_id" :error="$errors->first('district_id')">
                        <x-dashboard.select id="district_id" name="district_id" required>
                            <option value="">{{ __('Pilih kecamatan') }}</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" @selected(old('district_id', $vendor->district_id) == $district->id)>{{ $district->name }}</option>
                            @endforeach
                        </x-dashboard.select>
                    </x-dashboard.field>
                </div>
                <x-dashboard.field label="{{ __('Alamat Lengkap') }}" for="address" :error="$errors->first('address')">
                    <textarea id="address" name="address" rows="2" required
                              class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-rn-text placeholder:text-slate-400 transition focus:border-rn-primary focus:outline-none focus:ring-2 focus:ring-rn-primary/15">{{ old('address', $vendor->address) }}</textarea>
                </x-dashboard.field>
                <div class="border-t border-slate-100 pt-4">
                    <x-dashboard.btn variant="primary" icon="fa-floppy-disk" type="submit">{{ __('Simpan Info Toko') }}</x-dashboard.btn>
                </div>
            </form>
        </x-dashboard.card>

        {{-- Rekening Bank --}}
        <x-dashboard.card title="{{ __('Informasi Bank') }}" subtitle="{{ __('Rekening untuk pencairan dana hasil booking.') }}" data-rn-reveal>
            <form action="{{ route('vendor.profile.bank.update') }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid gap-4 md:grid-cols-2">
                    <x-dashboard.field label="{{ __('Nama Bank') }}" for="bank_name" :error="$errors->first('bank_name')">
                        <x-dashboard.input id="bank_name" name="bank_name" :value="old('bank_name', $vendor->bank_name)" placeholder="BCA / BNI / BRI / Mandiri" />
                    </x-dashboard.field>
                    <x-dashboard.field label="{{ __('Nomor Rekening') }}" for="bank_account" :error="$errors->first('bank_account')">
                        <x-dashboard.input id="bank_account" name="bank_account" :value="old('bank_account', $vendor->bank_account)" />
                    </x-dashboard.field>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <x-dashboard.btn variant="primary" icon="fa-floppy-disk" type="submit">{{ __('Simpan Bank') }}</x-dashboard.btn>
                </div>
            </form>
        </x-dashboard.card>

        {{-- Rating Manual --}}
        <x-dashboard.card title="{{ __('Rating dari Maps') }}"
                          subtitle="{{ __('Input manual dari rating toko di Google Maps. Akan tampil di kartu vendor saat user browse.') }}"
                          data-rn-reveal>
            <form action="{{ route('vendor.profile.rating.update') }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid gap-4 md:grid-cols-2">
                    <x-dashboard.field label="{{ __('Bintang (0-5)') }}" for="rating" :error="$errors->first('rating')" hint="{{ __('Contoh: 4.8') }}">
                        <x-dashboard.input id="rating" type="number" step="0.1" min="0" max="5" name="rating" :value="old('rating', $vendor->rating)" />
                    </x-dashboard.field>
                    <x-dashboard.field label="{{ __('Jumlah Reviewer') }}" for="rating_count" :error="$errors->first('rating_count')" hint="{{ __('Contoh: 152') }}">
                        <x-dashboard.input id="rating_count" type="number" min="0" name="rating_count" :value="old('rating_count', $vendor->rating_count)" />
                    </x-dashboard.field>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <x-dashboard.btn variant="primary" icon="fa-star" type="submit">{{ __('Simpan Rating') }}</x-dashboard.btn>
                </div>
            </form>
        </x-dashboard.card>

        {{-- Dokumen --}}
        <x-dashboard.card title="{{ __('Dokumen Verifikasi') }}"
                          subtitle="{{ __('Upload, ganti, hapus, atau preview dokumen pengajuan vendor.') }}"
                          data-rn-reveal>
            <div class="space-y-4">
                @foreach ($docTypes as $type => $label)
                    @php
                        $document = $documentsByType[$type] ?? null;
                        $statusMap = [
                            'approved' => ['label' => __('Disetujui'), 'tone' => 'success'],
                            'rejected' => ['label' => __('Ditolak'),   'tone' => 'danger'],
                            'pending'  => ['label' => __('Menunggu'),  'tone' => 'warning'],
                        ];
                        $status = $document ? ($statusMap[$document->status] ?? $statusMap['pending']) : null;
                    @endphp
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="leading-tight">
                                <p class="text-sm font-bold text-rn-text">{{ $label }}</p>
                                @if($document)
                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                        <x-dashboard.badge :tone="$status['tone']">{{ $status['label'] }}</x-dashboard.badge>
                                        <a href="{{ route('documents.vendor.media', $document) }}" target="_blank"
                                           class="text-xs font-semibold text-rn-primary hover:underline">
                                            <i class="fa fa-arrow-up-right-from-square text-[10px]"></i> {{ __('Preview') }}
                                        </a>
                                    </div>
                                    @if($document->notes)
                                        <p class="mt-1 text-xs text-slate-500">{{ $document->notes }}</p>
                                    @endif
                                @else
                                    <p class="mt-1 text-xs text-slate-500">{{ __('Belum ada dokumen.') }}</p>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <form action="{{ route('vendor.profile.document.upload', $type) }}" method="POST" enctype="multipart/form-data" class="inline">
                                    @csrf
                                    <label class="inline-flex h-9 cursor-pointer items-center gap-2 rounded-full bg-rn-primary px-3.5 text-xs font-semibold text-white transition hover:bg-rn-primary-dark">
                                        <i class="fa fa-upload text-[11px]"></i>
                                        {{ $document ? __('Upload Ulang') : __('Upload') }}
                                        <input type="file" name="document" accept="image/jpeg,image/png,application/pdf"
                                               class="hidden" onchange="this.form.submit()">
                                    </label>
                                </form>
                                @if($document)
                                    <form action="{{ route('vendor.profile.document.destroy', $document) }}" method="POST" class="inline"
                                          data-confirm-title="{{ __('Hapus dokumen?') }}"
                                          data-confirm-message="{{ __('Dokumen ini akan dihapus.') }}"
                                          data-confirm-confirm-text="{{ __('Ya, Hapus') }}"
                                          data-confirm-cancel-text="{{ __('Batal') }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dashboard.btn variant="danger" size="sm" type="submit" icon="fa-trash">{{ __('Hapus') }}</x-dashboard.btn>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-dashboard.card>

        {{-- Password --}}
        <x-dashboard.card title="{{ __('Ubah Password') }}" data-rn-reveal>
            <form action="{{ route('vendor.profile.password.update') }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <x-dashboard.field label="{{ __('Password Saat Ini') }}" for="current_password" :error="$errors->first('current_password')">
                    <x-dashboard.input id="current_password" type="password" name="current_password" required autocomplete="current-password" />
                </x-dashboard.field>
                <div class="grid gap-4 md:grid-cols-2">
                    <x-dashboard.field label="{{ __('Password Baru') }}" for="password" :error="$errors->first('password')">
                        <x-dashboard.input id="password" type="password" name="password" required autocomplete="new-password" />
                    </x-dashboard.field>
                    <x-dashboard.field label="{{ __('Konfirmasi Password Baru') }}" for="password_confirmation">
                        <x-dashboard.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </x-dashboard.field>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <x-dashboard.btn variant="primary" icon="fa-key" type="submit">{{ __('Update Password') }}</x-dashboard.btn>
                </div>
            </form>
        </x-dashboard.card>

        {{-- Hapus akun --}}
        <x-dashboard.card title="{{ __('Hapus Akun') }}" data-rn-reveal>
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p>{{ __('Tindakan ini tidak bisa dibatalkan. Data toko, dokumen, kendaraan, dan riwayat akan dihapus.') }}</p>
            </div>
            <form action="{{ route('vendor.profile.account.destroy') }}" method="POST" class="mt-4 space-y-4"
                  data-confirm-title="{{ __('Hapus akun vendor?') }}"
                  data-confirm-message="{{ __('Akun + toko + dokumen + kendaraan + riwayat akan dihapus dan tidak bisa dikembalikan.') }}"
                  data-confirm-confirm-text="{{ __('Ya, Hapus Akun') }}"
                  data-confirm-cancel-text="{{ __('Batal') }}">
                @csrf @method('DELETE')
                <x-dashboard.field label='{{ __("Ketik HAPUS AKUN untuk konfirmasi") }}' for="confirmation_text" :error="$errors->vendorDeletion->first('confirmation_text')">
                    <x-dashboard.input id="confirmation_text" name="confirmation_text" placeholder="HAPUS AKUN" required />
                </x-dashboard.field>
                <x-dashboard.field label="{{ __('Password Akun') }}" for="delete_password" :error="$errors->vendorDeletion->first('password')">
                    <x-dashboard.input id="delete_password" type="password" name="password" required autocomplete="current-password" />
                </x-dashboard.field>
                <x-dashboard.btn variant="danger" icon="fa-triangle-exclamation" type="submit">{{ __('Hapus Akun Saya') }}</x-dashboard.btn>
            </form>
        </x-dashboard.card>
    </div>

    {{-- Sidebar shortcuts --}}
    <aside class="space-y-6">
        <x-dashboard.card title="{{ __('Shortcut') }}" data-rn-reveal>
            <div class="space-y-2 text-sm">
                <a href="{{ route('vendor.dashboard') }}" class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-50">
                    <span class="flex items-center gap-2 text-rn-text"><i class="fa fa-house text-rn-primary text-xs"></i> {{ __('Kembali ke Dashboard') }}</span>
                    <i class="fa fa-chevron-right text-xs text-slate-400"></i>
                </a>
                <a href="{{ route('vendor.vehicles.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-50">
                    <span class="flex items-center gap-2 text-rn-text"><i class="fa fa-motorcycle text-rn-primary text-xs"></i> {{ __('Kelola Kendaraan') }}</span>
                    <i class="fa fa-chevron-right text-xs text-slate-400"></i>
                </a>
                <a href="{{ route('vendor.bookings.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-50">
                    <span class="flex items-center gap-2 text-rn-text"><i class="fa fa-clipboard-list text-rn-primary text-xs"></i> {{ __('Kelola Pesanan') }}</span>
                    <i class="fa fa-chevron-right text-xs text-slate-400"></i>
                </a>
            </div>
        </x-dashboard.card>

        <x-dashboard.card data-rn-reveal>
            <div class="rounded-xl border border-rn-primary/20 bg-rn-primary/5 p-4 text-sm text-rn-primary">
                <p class="font-bold">{{ __('Tips Vendor') }}</p>
                <p class="mt-1 text-xs leading-relaxed">{{ __('Lengkapi semua data + foto cover supaya kartu vendor kamu tampil maksimal di halaman browse user.') }}</p>
            </div>
        </x-dashboard.card>
    </aside>
</div>
@endsection
