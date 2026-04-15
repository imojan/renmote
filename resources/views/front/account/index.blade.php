@extends('layouts.front')

@section('title', 'Akun Saya')

@section('content')
<section class="section account-page-wrap">
    @php
        $profilePhotoUrl = $user->profile_photo_path
            ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path)
            : null;
        $rawUsername = trim((string) $user->username);
        $generatedUsername = \Illuminate\Support\Str::slug($user->name, '_');
        $hasInvalidUsernameFormat = $rawUsername === '' || preg_match('/[{}$]/', $rawUsername);
        $usernameDisplay = $hasInvalidUsernameFormat ? $generatedUsername : $rawUsername;
        $ktpDocument = $documentsByType->get('ktp');
        $simDocument = $documentsByType->get('sim');
        $canUploadKtp = !$ktpDocument;
        $canUploadSim = !$simDocument;
    @endphp

    <div class="account-shell">
        <aside class="account-side-panel">
            <div class="account-side-user">
                @if($profilePhotoUrl)
                    <img src="{{ $profilePhotoUrl }}" alt="{{ $user->name }}" class="account-side-avatar">
                @else
                    <div class="account-side-avatar account-side-avatar-fallback">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <p class="account-side-name">{{ $user->name }}</p>
                    <p class="account-side-username">{{ '@' . $usernameDisplay }}</p>
                </div>
            </div>

            <h3 class="account-side-title">Akun Saya</h3>
            <nav class="account-side-nav">
                <a href="#account-settings">Pengaturan Profil</a>
                <a href="#account-addresses">Alamat</a>
                <a href="#account-documents">Dokumen Penting</a>
                <a href="#account-password">Ubah Password</a>
                <a href="#account-wishlist">Wishlist</a>
                <a href="#account-bookings">Riwayat Pemesanan</a>
            </nav>
        </aside>

        <div class="account-main-panel">
            <header class="account-main-head">
                <div>
                    <h2>Akun Saya</h2>
                    <p>Kelola informasi profil, alamat, dokumen, password, wishlist, dan riwayat pemesanan.</p>
                </div>
                <div class="account-main-head-actions">
                    <a href="#account-wishlist" class="account-pill-btn">Wishlist</a>
                    <a href="#account-bookings" class="account-pill-btn account-pill-btn-primary">Riwayat</a>
                </div>
            </header>

            @if(session('success'))
                <div class="account-alert account-alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="account-alert account-alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="account-alert account-alert-error">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section id="account-settings" class="account-block">
                <div class="account-block-head">
                    <div>
                        <h3>Pengaturan Profil</h3>
                        <p>Data profil ini digunakan saat user melakukan transaksi penyewaan.</p>
                    </div>
                    <span class="account-tag">My Account</span>
                </div>

                <form action="{{ route('user.account.profile.update') }}" method="POST" enctype="multipart/form-data" class="account-form-grid">
                    @csrf
                    @method('PATCH')

                    <div class="account-profile-photo-wrap">
                        <div class="account-profile-photo-preview">
                            @if($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="{{ $user->name }}">
                            @else
                                <div class="account-profile-photo-fallback">
                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="account-profile-photo-control">
                            <label for="profile_photo">Foto Profil</label>
                            <input id="profile_photo" type="file" name="profile_photo" accept=".jpg,.jpeg,.png">
                            <small>JPG/PNG maksimal 2MB.</small>
                        </div>
                    </div>

                    <div class="account-two-col">
                        <div>
                            <label>Username</label>
                            <input type="text" name="username" value="{{ old('username', $usernameDisplay) }}" placeholder="Masukkan username">
                        </div>
                        <div>
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="account-two-col">
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div>
                            <label>Nomor Telepon</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="account-two-col">
                        <div>
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Pilih Gender</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="account-action-row">
                        <button type="submit" class="account-main-btn">Simpan Perubahan Profil</button>
                    </div>
                </form>
            </section>

            <section id="account-addresses" class="account-block">
                <div class="account-block-head">
                    <div>
                        <h3>Alamat User</h3>
                        <p>Atur alamat tetap maupun sementara untuk kebutuhan transaksi sewa.</p>
                    </div>
                    <span class="account-tag">Alamat</span>
                </div>

                @if($user->addresses->count() > 0)
                    <div class="account-address-list">
                        @foreach($user->addresses as $address)
                            <article class="account-address-item {{ $address->is_default ? 'is-default' : '' }}">
                                <div class="account-address-meta">
                                    <div class="account-address-title-row">
                                        <strong>{{ $address->label }}</strong>
                                        <span class="account-address-type-pill {{ $address->address_type === 'temporary' ? 'is-temporary' : '' }}">
                                            {{ $address->address_type === 'temporary' ? 'Sementara' : 'Tetap' }}
                                        </span>
                                        @if($address->is_default)
                                            <span class="account-default-pill">Default</span>
                                        @endif
                                    </div>
                                    <p>{{ $address->street }}, {{ $address->district->name ?? '-' }}, {{ $address->city }} {{ $address->postal_code }}</p>
                                </div>

                                <div class="account-address-actions">
                                    <button type="button" class="account-mini-btn js-toggle-edit" data-target="address-form-{{ $address->id }}">Edit</button>

                                    @if(!$address->is_default)
                                        <form action="{{ route('user.account.address.default', $address) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="account-mini-btn">Jadikan Default</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('user.account.address.destroy', $address) }}" method="POST"
                                        data-confirm-title="Hapus alamat?"
                                        data-confirm-message="Alamat ini akan dihapus dari akun kamu."
                                        data-confirm-confirm-text="Ya, Hapus"
                                        data-confirm-cancel-text="Batal">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="account-mini-btn account-mini-btn-danger">Hapus</button>
                                    </form>
                                </div>

                                <form id="address-form-{{ $address->id }}" action="{{ route('user.account.address.update', $address) }}" method="POST" class="account-inline-edit">
                                    @csrf
                                    @method('PATCH')

                                    <div class="account-three-col">
                                        <div>
                                            <label>Label</label>
                                            <input type="text" name="label" value="{{ $address->label }}" required>
                                        </div>
                                        <div>
                                            <label>Tipe Alamat</label>
                                            <select name="address_type" required>
                                                <option value="permanent" {{ $address->address_type === 'permanent' ? 'selected' : '' }}>Tetap</option>
                                                <option value="temporary" {{ $address->address_type === 'temporary' ? 'selected' : '' }}>Sementara</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label>Kecamatan</label>
                                            <select name="district_id" required>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->id }}" {{ $address->district_id === $district->id ? 'selected' : '' }}>
                                                        {{ $district->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label>Alamat Jalan</label>
                                        <textarea name="street" rows="2" required>{{ $address->street }}</textarea>
                                    </div>

                                    <div class="account-two-col">
                                        <div>
                                            <label>Kota</label>
                                            <input type="text" name="city" value="{{ $address->city }}" required>
                                        </div>
                                        <div>
                                            <label>Kode Pos</label>
                                            <input type="text" name="postal_code" value="{{ $address->postal_code }}" required>
                                        </div>
                                    </div>

                                    <label class="account-checkbox">
                                        <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }}>
                                        <span>Jadikan default</span>
                                    </label>

                                    <div class="account-inline-actions">
                                        <button type="submit" class="account-mini-btn account-mini-btn-primary">Simpan Edit</button>
                                        <button type="button" class="account-mini-btn js-cancel-edit" data-target="address-form-{{ $address->id }}">Batal</button>
                                    </div>
                                </form>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="account-empty-box">
                        Belum ada alamat tersimpan. Kamu bisa langsung tambahkan alamat tetap atau sementara.
                    </div>
                @endif

                <div class="account-divider"></div>

                <h4 class="account-subtitle">Tambah Alamat Baru</h4>
                <form action="{{ route('user.account.address.store') }}" method="POST" class="account-form-grid">
                    @csrf

                    <div class="account-three-col">
                        <div>
                            <label>Label Alamat</label>
                            <input type="text" name="label" value="{{ old('label') }}" placeholder="Rumah / Kost / Kantor" required>
                        </div>
                        <div>
                            <label>Tipe Alamat</label>
                            <select name="address_type" required>
                                <option value="permanent" {{ old('address_type', 'permanent') === 'permanent' ? 'selected' : '' }}>Tetap</option>
                                <option value="temporary" {{ old('address_type') === 'temporary' ? 'selected' : '' }}>Sementara</option>
                            </select>
                        </div>
                        <div>
                            <label>Kecamatan</label>
                            <select name="district_id" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ (string) old('district_id') === (string) $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label>Alamat Jalan</label>
                        <textarea name="street" rows="2" placeholder="Nama jalan, nomor rumah, patokan" required>{{ old('street') }}</textarea>
                    </div>

                    <div class="account-two-col">
                        <div>
                            <label>Kota</label>
                            <input type="text" name="city" value="{{ old('city', 'Malang') }}" required>
                        </div>
                        <div>
                            <label>Kode Pos</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required>
                        </div>
                    </div>

                    <label class="account-checkbox">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                        <span>Jadikan sebagai alamat default</span>
                    </label>

                    <div class="account-action-row">
                        <button type="submit" class="account-main-btn">Simpan Alamat Baru</button>
                    </div>
                </form>
            </section>

            <section id="account-documents" class="account-block">
                <div class="account-block-head">
                    <div>
                        <h3>Dokumen Penting</h3>
                        <p>Unggah KTP/KTM (wajib) dan SIM (opsional) sebagai data pendukung review transaksi oleh admin/vendor.</p>
                    </div>
                    <span class="account-tag">Verifikasi</span>
                </div>

                <div class="account-document-grid">
                    <article class="account-document-item">
                        <h4>Dokumen KTP/KTM</h4>
                        <p>Status:
                            <span class="account-doc-status account-doc-status-{{ $ktpDocument?->status ?? 'pending' }}">
                                {{ strtoupper($ktpDocument?->status ?? 'belum upload') }}
                            </span>
                        </p>
                        @if($ktpDocument)
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($ktpDocument->file_path) }}" target="_blank">Lihat dokumen KTP/KTM</a>
                            <form action="{{ route('user.account.documents.destroy', 'ktp') }}" method="POST"
                                class="account-doc-action-form"
                                data-confirm-title="Hapus dokumen KTP/KTM?"
                                data-confirm-message="Dokumen akan dihapus. Kamu bisa unggah ulang setelahnya."
                                data-confirm-confirm-text="Ya, Hapus"
                                data-confirm-cancel-text="Batal">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="account-mini-btn account-mini-btn-danger">Hapus dokumen</button>
                            </form>
                        @else
                            <span class="account-doc-empty">Belum ada file KTP/KTM.</span>
                        @endif
                    </article>

                    <article class="account-document-item">
                        <h4>Dokumen SIM (Opsional)</h4>
                        <p>Status:
                            <span class="account-doc-status account-doc-status-{{ $simDocument?->status ?? 'pending' }}">
                                {{ strtoupper($simDocument?->status ?? 'belum upload') }}
                            </span>
                        </p>
                        @if($simDocument)
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($simDocument->file_path) }}" target="_blank">Lihat dokumen SIM</a>
                            <form action="{{ route('user.account.documents.destroy', 'sim') }}" method="POST"
                                class="account-doc-action-form"
                                data-confirm-title="Hapus dokumen SIM?"
                                data-confirm-message="Dokumen akan dihapus. Kamu bisa unggah ulang setelahnya."
                                data-confirm-confirm-text="Ya, Hapus"
                                data-confirm-cancel-text="Batal">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="account-mini-btn account-mini-btn-danger">Hapus dokumen</button>
                            </form>
                        @else
                            <span class="account-doc-empty">Belum ada file SIM.</span>
                        @endif
                    </article>
                </div>

                @if($canUploadKtp || $canUploadSim)
                    <form action="{{ route('user.account.documents.update') }}" method="POST" enctype="multipart/form-data" class="account-form-grid account-doc-upload-form">
                        @csrf

                        <div class="account-two-col account-doc-upload-grid">
                            @if($canUploadKtp)
                                <div>
                                    <label>Upload KTP/KTM</label>
                                    <input type="file" name="document_ktp" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            @endif

                            @if($canUploadSim)
                                <div>
                                    <label>Upload SIM (opsional)</label>
                                    <input type="file" name="document_sim" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            @endif
                        </div>

                        <small>Format file: JPG, PNG, atau PDF. Maksimal 4MB per file.</small>

                        <div class="account-action-row account-action-row-spaced">
                            <button type="submit" class="account-main-btn">Simpan Dokumen</button>
                        </div>
                    </form>
                @else
                    <div class="account-empty-box account-doc-upload-locked">
                        Semua dokumen sudah diunggah. Jika ingin unggah ulang, hapus dokumen pada kartu di atas terlebih dahulu.
                    </div>
                @endif
            </section>

            <section id="account-password" class="account-block">
                <div class="account-block-head">
                    <div>
                        <h3>Pengaturan Password</h3>
                        <p>Ubah password secara berkala untuk keamanan akun.</p>
                    </div>
                </div>

                <form action="{{ route('user.account.password.update') }}" method="POST" class="account-form-grid">
                    @csrf
                    @method('PUT')

                    <div class="account-two-col">
                        <div>
                            <label>Password Saat Ini</label>
                            <div class="account-password-field">
                                <input id="account_current_password" type="password" name="current_password" required>
                                <button type="button" class="account-password-toggle js-password-toggle" data-target="account_current_password" aria-label="Tampilkan password saat ini">
                                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label>Password Baru</label>
                            <div class="account-password-field">
                                <input id="account_new_password" type="password" name="password" required>
                                <button type="button" class="account-password-toggle js-password-toggle" data-target="account_new_password" aria-label="Tampilkan password baru">
                                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label>Konfirmasi Password Baru</label>
                        <div class="account-password-field">
                            <input id="account_password_confirmation" type="password" name="password_confirmation" required>
                            <button type="button" class="account-password-toggle js-password-toggle" data-target="account_password_confirmation" aria-label="Tampilkan konfirmasi password baru">
                                <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                    <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="account-action-row">
                        <button type="submit" class="account-main-btn">Update Password</button>
                    </div>
                </form>
            </section>

            <section id="account-wishlist" class="account-block">
                <div class="account-block-head">
                    <div>
                        <h3>Wishlist</h3>
                        <p>Bagian ini menampilkan data favorit yang sama saat user klik ikon love di navbar.</p>
                    </div>
                </div>

                <div class="account-empty-box">
                    Belum ada kendaraan di wishlist kamu.
                </div>

                <div class="account-action-row account-action-row-spaced">
                    <a href="{{ route('search') }}" class="account-main-btn account-main-btn-link">Cari Kendaraan</a>
                    <a href="{{ route('user.wishlist.index') }}" class="account-sub-btn">Buka Halaman Wishlist</a>
                </div>
            </section>

            <section id="account-bookings" class="account-block">
                <div class="account-block-head">
                    <div>
                        <h3>Riwayat Pemesanan</h3>
                        <p>Transaksi terbaru user. Untuk detail lengkap bisa buka halaman riwayat pemesanan.</p>
                    </div>
                </div>

                @if($bookings->count() > 0)
                    <div class="booking-history-grid">
                        @foreach($bookings as $booking)
                            <article class="booking-history-card">
                                <div class="booking-history-top">
                                    <div>
                                        <h3>{{ $booking->vehicle->name }}</h3>
                                        <p>{{ $booking->vehicle->vendor->store_name }}</p>
                                    </div>
                                    <span class="booking-status-pill
                                        @if($booking->status === 'pending') booking-status-pending
                                        @elseif($booking->status === 'confirmed') booking-status-confirmed
                                        @elseif($booking->status === 'completed') booking-status-completed
                                        @else booking-status-cancelled @endif">
                                        {{ $booking->status === 'cancelled' ? 'Declined' : ucfirst($booking->status) }}
                                    </span>
                                </div>

                                <div class="booking-history-meta">
                                    <div><span>Tanggal</span> {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</div>
                                    <div><span>Total</span> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                    <div>
                                        <span>Pembayaran</span>
                                        @if($booking->payment)
                                            {{ strtoupper($booking->payment->payment_type) }} - {{ ucfirst($booking->payment->status) }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>

                                <div class="booking-history-actions">
                                    <a href="{{ route('user.bookings.show', $booking->id) }}" class="booking-btn-secondary">Lihat Detail</a>

                                    @if($booking->status === 'pending')
                                        <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST"
                                            data-confirm-title="Batalkan booking?"
                                            data-confirm-message="Pesanan ini akan dibatalkan dan tidak bisa dikembalikan otomatis."
                                            data-confirm-confirm-text="Ya, Batalkan"
                                            data-confirm-cancel-text="Tidak">
                                            @csrf
                                            <button type="submit" class="booking-btn-danger">Batalkan</button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="account-empty-box">
                        Belum ada riwayat pemesanan. Mulai cari kendaraan yang kamu butuhkan.
                    </div>
                @endif

                <div class="account-action-row account-action-row-spaced">
                    <a href="{{ route('user.bookings.index') }}" class="account-main-btn account-main-btn-link">Buka Riwayat Lengkap</a>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.js-toggle-edit').forEach((button) => {
        button.addEventListener('click', () => {
            const formId = button.dataset.target;
            const form = document.getElementById(formId);
            if (!form) {
                return;
            }

            form.classList.toggle('is-open');
        });
    });

    document.querySelectorAll('.js-cancel-edit').forEach((button) => {
        button.addEventListener('click', () => {
            const formId = button.dataset.target;
            const form = document.getElementById(formId);
            if (!form) {
                return;
            }

            form.classList.remove('is-open');
        });
    });
</script>
@endpush
