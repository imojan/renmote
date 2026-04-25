@extends('layouts.front')

@section('title', 'Booking ' . $vehicle->name)

@section('content')
<section class="section booking-front-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Booking Kendaraan</h2>
            <p class="booking-front-subtitle">Lengkapi tanggal sewa untuk melanjutkan pemesanan tanpa pindah ke dashboard.</p>
        </div>
        <a href="{{ route('vehicles.show', $vehicle) }}" class="booking-back-link">
            <i class="fa fa-arrow-left"></i> Kembali ke detail kendaraan
        </a>
    </div>

    @if(session('error'))
        <div class="booking-alert booking-alert-error">{{ session('error') }}</div>
    @endif

    <div class="booking-create-grid">
        <article class="booking-vehicle-card">
            <div class="booking-vehicle-image-wrap">
                @if($vehicle->image)
                    <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="booking-vehicle-image">
                @else
                    <div class="booking-vehicle-placeholder"><i class="fa fa-motorcycle"></i></div>
                @endif
            </div>

            <div class="booking-vehicle-body">
                <h3>{{ $vehicle->name }}</h3>
                <p>
                    {{ ucfirst($vehicle->category) }}
                    @if($vehicle->engine_cc)
                        • {{ $vehicle->engine_cc }}cc
                    @endif
                    • {{ $vehicle->year }}
                </p>
                <div class="booking-vehicle-vendor">{{ $vehicle->vendor->store_name }} • {{ $vehicle->vendor->district->name }}</div>
                <div class="booking-vehicle-price">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}<span>/hari</span></div>
            </div>
        </article>

        <article class="booking-form-card">
            <form action="{{ route('user.bookings.confirmation', $vehicle) }}" method="GET">

                <div id="bookingAvailabilityWarning" class="booking-alert booking-alert-error hidden"></div>
                <div id="bookingAvailabilitySuccess" class="booking-alert booking-alert-success hidden"></div>

                <div class="booking-date-grid">
                    <div>
                        <label class="booking-label">Tanggal Mulai</label>
                        <input type="date" id="startDateInput" name="start_date" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" class="booking-input @error('start_date') booking-input-error @enderror">
                        @error('start_date')
                            <p class="booking-error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="booking-label">Tanggal Selesai</label>
                        <input type="date" id="endDateInput" name="end_date" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" class="booking-input @error('end_date') booking-input-error @enderror">
                        @error('end_date')
                            <p class="booking-error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="booking-payment-info">
                    <h4>Informasi Pembayaran</h4>
                    <ul>
                        <li>DP (Down Payment) sebesar <strong>30%</strong> dari total harga.</li>
                        <li>Sisa pembayaran dilunasi saat pengambilan motor.</li>
                        <li>Pembatalan gratis selama status booking masih pending.</li>
                    </ul>
                </div>

                <div class="booking-form-actions">
                    <button id="bookingSubmitBtn" type="submit" class="booking-btn-primary">
                        Konfirmasi Booking
                    </button>
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="booking-btn-secondary">Batal</a>
                </div>
            </form>
        </article>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const startDateInput = document.getElementById('startDateInput');
    const endDateInput = document.getElementById('endDateInput');
    const warningBox = document.getElementById('bookingAvailabilityWarning');
    const successBox = document.getElementById('bookingAvailabilitySuccess');
    const submitBtn = document.getElementById('bookingSubmitBtn');
    const checkUrl = "{{ route('user.bookings.checkAvailability', $vehicle) }}";

    let debounceTimer = null;

    function toDateInputValue(dateObj) {
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function enforceEndDateMin() {
        if (!startDateInput.value) {
            endDateInput.min = "{{ date('Y-m-d') }}";
            return;
        }

        const start = new Date(startDateInput.value);
        start.setDate(start.getDate() + 1);
        const minEndDate = toDateInputValue(start);
        endDateInput.min = minEndDate;

        if (endDateInput.value && endDateInput.value < minEndDate) {
            endDateInput.value = '';
        }
    }

    function hideMessages() {
        warningBox.classList.add('hidden');
        successBox.classList.add('hidden');
        warningBox.textContent = '';
        successBox.textContent = '';
    }

    function setSubmitDisabled(disabled) {
        submitBtn.disabled = disabled;
    }

    function formatRangeText(overlaps) {
        if (!overlaps || overlaps.length === 0) {
            return '';
        }

        const formatted = overlaps.map((item) => `${item.start_date} s/d ${item.end_date}`);
        return ` Rentang terisi: ${formatted.join(', ')}`;
    }

    async function checkAvailability() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (!startDate && !endDate) {
            hideMessages();
            setSubmitDisabled(false);
            return;
        }

        try {
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);

            const response = await fetch(`${checkUrl}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            hideMessages();

            if (data.available) {
                successBox.textContent = data.message;
                successBox.classList.remove('hidden');
                setSubmitDisabled(false);
            } else {
                warningBox.textContent = data.message + formatRangeText(data.overlaps);
                warningBox.classList.remove('hidden');
                setSubmitDisabled(true);
            }
        } catch (error) {
            setSubmitDisabled(false);
        }
    }

    function scheduleCheck() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(checkAvailability, 250);
    }

    startDateInput.addEventListener('change', () => {
        enforceEndDateMin();
        scheduleCheck();
    });
    endDateInput.addEventListener('change', scheduleCheck);

    enforceEndDateMin();

    if (startDateInput.value || endDateInput.value) {
        checkAvailability();
    }
</script>
@endpush
