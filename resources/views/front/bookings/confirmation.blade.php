@extends('layouts.front')

@section('title', 'Konfirmasi Penyewaan')

@section('content')
<section class="section booking-front-section booking-checkout-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Checkout Penyewaan</h2>
            <p class="booking-front-subtitle">Pastikan detail sewa, alamat, dokumen, dan metode pembayaran sudah benar.</p>
        </div>
        <a href="{{ route('user.bookings.create', $vehicle) }}" class="booking-back-link">
            <i class="fa fa-arrow-left"></i> Kembali pilih tanggal
        </a>
    </div>

    @include('front.bookings.partials.checkout-steps', ['currentStep' => 1])

    @if(session('error'))
        <div class="booking-alert booking-alert-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="booking-alert booking-alert-error">
            <strong>Periksa kembali data checkout:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $ktpDocument = $documentsByType->get('ktp');
        $simDocument = $documentsByType->get('sim');
        $selectedMethod = old('fulfillment_method', 'pickup');
        $oldAddressId = old('address_id', optional($user->addresses->firstWhere('is_default', true))->id);
        $useNewAddress = old('use_new_address', $user->addresses->isEmpty() ? '1' : '0');
    @endphp

    <form action="{{ route('user.bookings.store', $vehicle) }}" method="POST" enctype="multipart/form-data" class="booking-checkout-grid">
        @csrf
        <input type="hidden" name="start_date" value="{{ $startDate }}">
        <input type="hidden" name="end_date" value="{{ $endDate }}">
        <input type="hidden" name="payment_method" value="qris">

        <div class="booking-checkout-main">
            <article class="booking-checkout-card">
                <header class="booking-checkout-card-head">
                    <h3>1. Metode Pengambilan Kendaraan</h3>
                    <p>Pilih apakah kendaraan diambil langsung ke outlet vendor atau diantar ke alamat user.</p>
                </header>

                <div class="booking-fulfillment-switch">
                    <label class="booking-radio-card {{ $selectedMethod === 'pickup' ? 'is-selected' : '' }}">
                        <input type="radio" name="fulfillment_method" value="pickup" {{ $selectedMethod === 'pickup' ? 'checked' : '' }}>
                        <span>
                            <strong>Ambil di Dealer/Outlet Vendor</strong>
                            <small>Pengambilan mandiri sesuai lokasi vendor.</small>
                        </span>
                    </label>

                    <label class="booking-radio-card {{ $selectedMethod === 'delivery' ? 'is-selected' : '' }}">
                        <input type="radio" name="fulfillment_method" value="delivery" {{ $selectedMethod === 'delivery' ? 'checked' : '' }}>
                        <span>
                            <strong>Diantar ke Alamat User</strong>
                            <small>Pilih alamat utama/alamat lain atau tambahkan alamat baru.</small>
                        </span>
                    </label>
                </div>

                <div id="pickupOutletSection" class="booking-pickup-outlet {{ $selectedMethod === 'pickup' ? '' : 'hidden' }}">
                    <h4>Alamat Outlet Vendor</h4>
                    <p>
                        <strong>{{ $vehicle->vendor->store_name }}</strong><br>
                        {{ $vehicle->vendor->address ?: 'Alamat outlet belum diisi vendor.' }}
                        @if($vehicle->vendor->district)
                            , {{ $vehicle->vendor->district->name }}
                        @endif
                    </p>
                
                </div>

                <div id="deliveryAddressSection" class="booking-delivery-section {{ $selectedMethod === 'delivery' ? '' : 'hidden' }}">
                    @if($user->addresses->count() > 0)
                        <div class="booking-delivery-list">
                            @foreach($user->addresses as $address)
                                <label class="booking-address-card {{ (string) $oldAddressId === (string) $address->id && $useNewAddress !== '1' ? 'is-selected' : '' }}">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" {{ (string) $oldAddressId === (string) $address->id && $useNewAddress !== '1' ? 'checked' : '' }}>
                                    <div>
                                        <div class="booking-address-title-row">
                                            <strong>{{ $address->label }}</strong>
                                            @if($address->is_default)
                                                <span class="booking-address-pill">Default</span>
                                            @endif
                                            <span class="booking-address-pill {{ $address->address_type === 'temporary' ? 'is-muted' : '' }}">
                                                {{ $address->address_type === 'temporary' ? 'Sementara' : 'Tetap' }}
                                            </span>
                                        </div>
                                        <p>{{ $address->street }}, {{ $address->district->name ?? '-' }}, {{ $address->city }} {{ $address->postal_code }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <label class="booking-checkbox-line">
                        <input type="checkbox" id="useNewAddressInput" name="use_new_address" value="1" {{ $useNewAddress === '1' ? 'checked' : '' }}>
                        <span>Gunakan alamat lain (baru) untuk pesanan ini</span>
                    </label>

                    <div id="newAddressFields" class="booking-new-address {{ $useNewAddress === '1' || $user->addresses->isEmpty() ? '' : 'hidden' }}">
                        <div class="booking-date-grid booking-three-col">
                            <div>
                                <label class="booking-label">Label Alamat</label>
                                <input type="text" name="new_address_label" value="{{ old('new_address_label') }}" class="booking-input" placeholder="Rumah / Kost / Kantor">
                            </div>
                            <div>
                                <label class="booking-label">Tipe Alamat</label>
                                <select name="new_address_type" class="booking-input">
                                    <option value="permanent" {{ old('new_address_type', 'temporary') === 'permanent' ? 'selected' : '' }}>Tetap</option>
                                    <option value="temporary" {{ old('new_address_type', 'temporary') === 'temporary' ? 'selected' : '' }}>Sementara</option>
                                </select>
                            </div>
                            <div>
                                <label class="booking-label">Kecamatan</label>
                                <select name="new_address_district_id" class="booking-input">
                                    <option value="">Pilih Kecamatan</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ (string) old('new_address_district_id') === (string) $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="booking-label">Alamat Jalan</label>
                            <textarea name="new_address_street" rows="2" class="booking-textarea" placeholder="Nama jalan, nomor rumah, patokan">{{ old('new_address_street') }}</textarea>
                        </div>

                        <div class="booking-date-grid">
                            <div>
                                <label class="booking-label">Kota</label>
                                <input type="text" name="new_address_city" value="{{ old('new_address_city', 'Malang') }}" class="booking-input">
                            </div>
                            <div>
                                <label class="booking-label">Kode Pos</label>
                                <input type="text" name="new_address_postal_code" value="{{ old('new_address_postal_code') }}" class="booking-input">
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('user.account.index') }}#account-addresses" class="booking-inline-link">Kelola alamat lengkap di halaman akun</a>
                </div>
            </article>

            <article class="booking-checkout-card">
                <header class="booking-checkout-card-head">
                    <h3>2. Detail Kendaraan & Ringkasan Sewa</h3>
                    <p>Ringkasan kendaraan dan biaya otomatis berdasarkan tanggal sewa yang dipilih.</p>
                </header>

                <div class="booking-checkout-vehicle">
                    <div class="booking-checkout-vehicle-image">
                        @if($vehicle->image)
                            <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}">
                        @else
                            <div class="booking-vehicle-placeholder"><i class="fa fa-motorcycle"></i></div>
                        @endif
                    </div>
                    <div>
                        <h4>{{ $vehicle->name }}</h4>
                        <p>
                            {{ ucfirst($vehicle->category) }}
                            @if($vehicle->engine_cc)
                                • {{ $vehicle->engine_cc }}cc
                            @endif
                            • {{ $vehicle->year }}
                        </p>
                        <p>{{ $vehicle->vendor->store_name }} • {{ $vehicle->vendor->district->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="booking-summary-grid">
                    <div>
                        <label>Periode Sewa</label>
                        <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                    </div>
                    <div>
                        <label>Durasi</label>
                        <strong>{{ $summary['days'] }} hari</strong>
                    </div>
                    <div>
                        <label>Harga Asli Sewa</label>
                        <strong>Rp {{ number_format($summary['total_price'], 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <label>DP (30%)</label>
                        <strong class="booking-value-primary">Rp {{ number_format($summary['dp_amount'], 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <label>Sisa Pembayaran</label>
                        <strong>Rp {{ number_format($summary['remaining_amount'], 0, ',', '.') }}</strong>
                    </div>
                </div>
            </article>

            <article class="booking-checkout-card">
                <header class="booking-checkout-card-head">
                    <h3>3. Dokumen Pendukung</h3>
                    <p>KTP/KTM wajib. SIM opsional. Jika sudah di akun, dokumen otomatis dilampirkan ke admin/vendor.</p>
                </header>

                <div class="booking-document-grid">
                    <div class="booking-document-item {{ $ktpDocument ? 'is-ready' : '' }}">
                        <div class="booking-document-top">
                            <h4>KTP/KTM</h4>
                            <span class="booking-document-status {{ $ktpDocument ? 'is-ok' : 'is-warning' }}">
                                <i class="fa {{ $ktpDocument ? 'fa-check-circle' : 'fa-circle-exclamation' }}"></i>
                                {{ $ktpDocument ? 'Terlampir otomatis' : 'Wajib upload sekarang' }}
                            </span>
                        </div>

                        @if($ktpDocument)
                            <a href="{{ route('documents.user.media', $ktpDocument) }}" target="_blank" class="booking-inline-link">Lihat dokumen KTP/KTM</a>
                            <p class="booking-help-text">Boleh unggah ulang jika ingin mengganti file.</p>
                        @endif

                        <label class="booking-label">Upload KTP/KTM {{ $ktpDocument ? '(opsional)' : '(wajib)' }}</label>
                        <input type="file" name="document_ktp" accept=".jpg,.jpeg,.png,.pdf" class="booking-input-file" {{ $ktpDocument ? '' : 'required' }}>
                    </div>

                    <div class="booking-document-item {{ $simDocument ? 'is-ready' : '' }}">
                        <div class="booking-document-top">
                            <h4>SIM (Opsional)</h4>
                            <span class="booking-document-status {{ $simDocument ? 'is-ok' : '' }}">
                                <i class="fa {{ $simDocument ? 'fa-check-circle' : 'fa-circle-info' }}"></i>
                                {{ $simDocument ? 'Sudah tersedia' : 'Boleh diunggah jika ada' }}
                            </span>
                        </div>

                        @if($simDocument)
                            <a href="{{ route('documents.user.media', $simDocument) }}" target="_blank" class="booking-inline-link">Lihat dokumen SIM</a>
                        @endif

                        <label class="booking-label">Upload SIM (opsional)</label>
                        <input type="file" name="document_sim" accept=".jpg,.jpeg,.png,.pdf" class="booking-input-file">
                    </div>
                </div>
            </article>
        </div>

        <aside class="booking-checkout-side">
            <article class="booking-checkout-card">
                <header class="booking-checkout-card-head">
                    <h3>4. Metode Pembayaran</h3>
                    <p>Sementara mendukung QRIS untuk proses DP.</p>
                </header>

                <div class="booking-payment-method">
                    <div class="booking-payment-method-row">
                        <img src="{{ asset('images/logo-qris.png') }}" alt="QRIS" loading="lazy">
                        <div>
                            <strong>QRIS</strong>
                            <p>Bayar DP 30% melalui scan QR code.</p>
                        </div>
                    </div>
                </div>

                <div class="booking-price-breakdown">
                    <div><span>Total Sewa</span><strong>Rp {{ number_format($summary['total_price'], 0, ',', '.') }}</strong></div>
                    <div><span>DP Dibayar Sekarang</span><strong class="booking-value-primary">Rp {{ number_format($summary['dp_amount'], 0, ',', '.') }}</strong></div>
                    <div><span>Sisa Saat Pengambilan</span><strong>Rp {{ number_format($summary['remaining_amount'], 0, ',', '.') }}</strong></div>
                </div>

                <button type="submit" class="booking-btn-primary booking-btn-block">Buat Pesanan Penyewaan</button>
                <a href="{{ route('user.bookings.create', $vehicle) }}" class="booking-btn-secondary booking-btn-block">Ubah Tanggal Sewa</a>

                <p class="booking-checkout-note">Dengan melanjutkan, kamu menyetujui proses verifikasi dokumen dan pembayaran oleh admin/vendor.</p>
            </article>
        </aside>
    </form>
</section>
@endsection

@push('scripts')
<script>
    const methodInputs = document.querySelectorAll('input[name="fulfillment_method"]');
    const useNewAddressInput = document.getElementById('useNewAddressInput');
    const deliverySection = document.getElementById('deliveryAddressSection');
    const pickupOutletSection = document.getElementById('pickupOutletSection');
    const newAddressFields = document.getElementById('newAddressFields');
    const addressInputs = document.querySelectorAll('input[name="address_id"]');
    const addressCards = document.querySelectorAll('.booking-address-card');

    function updateMethodCards() {
        document.querySelectorAll('.booking-radio-card').forEach((card) => {
            const radio = card.querySelector('input[type="radio"]');
            if (!radio) return;
            card.classList.toggle('is-selected', radio.checked);
        });
    }

    function toggleDeliverySection() {
        const selectedMethod = document.querySelector('input[name="fulfillment_method"]:checked')?.value;
        const isDelivery = selectedMethod === 'delivery';
        const isPickup = selectedMethod === 'pickup';
        if (!deliverySection) return;

        deliverySection.classList.toggle('hidden', !isDelivery);
        if (pickupOutletSection) {
            pickupOutletSection.classList.toggle('hidden', !isPickup);
        }

        if (!isDelivery) {
            addressInputs.forEach((input) => {
                input.required = false;
            });
            return;
        }

        toggleNewAddressFields();
    }

    function updateAddressCards() {
        addressCards.forEach((card) => {
            const radio = card.querySelector('input[name="address_id"]');
            if (!radio) return;
            card.classList.toggle('is-selected', radio.checked);
        });
    }

    function toggleNewAddressFields() {
        if (!newAddressFields) return;

        const checkedNewAddress = useNewAddressInput ? useNewAddressInput.checked : false;
        newAddressFields.classList.toggle('hidden', !checkedNewAddress);

        const newAddressInputs = newAddressFields.querySelectorAll('input, select, textarea');
        newAddressInputs.forEach((input) => {
            if (['new_address_label', 'new_address_type', 'new_address_street', 'new_address_district_id', 'new_address_city', 'new_address_postal_code'].includes(input.name)) {
                input.required = checkedNewAddress;
            }
        });

        addressInputs.forEach((input) => {
            input.required = !checkedNewAddress;
            if (checkedNewAddress) {
                input.checked = false;
            }
        });

        updateAddressCards();
    }

    methodInputs.forEach((input) => {
        input.addEventListener('change', () => {
            updateMethodCards();
            toggleDeliverySection();
        });
    });

    if (useNewAddressInput) {
        useNewAddressInput.addEventListener('change', toggleNewAddressFields);
    }

    addressInputs.forEach((input) => {
        input.addEventListener('change', () => {
            if (useNewAddressInput) {
                useNewAddressInput.checked = false;
            }
            toggleNewAddressFields();
        });
    });

    updateMethodCards();
    toggleDeliverySection();
    updateAddressCards();
</script>
@endpush
