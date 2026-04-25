@extends('layouts.front')

@section('title', 'Pembayaran Booking')

@section('content')
<section class="section booking-front-section booking-checkout-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Pembayaran DP</h2>
            <p class="booking-front-subtitle">Lakukan pembayaran sebelum batas waktu berakhir untuk mengamankan pesanan.</p>
        </div>
        <a href="{{ route('user.bookings.show', $booking->id) }}" class="booking-back-link">
            <i class="fa fa-arrow-left"></i> Lihat detail pesanan
        </a>
    </div>

    @include('front.bookings.partials.checkout-steps', ['currentStep' => 2])

    @if(session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="booking-alert booking-alert-error">{{ session('error') }}</div>
    @endif

    <div class="booking-payment-layout">
        <article class="booking-checkout-card">
            <header class="booking-checkout-card-head">
                <h3>Detail Pembayaran QRIS</h3>
                <p>Scan kode berikut melalui aplikasi pembayaran yang mendukung QRIS.</p>
            </header>

            @php
                $payment = $booking->payment;
                $isExpired = $payment->expires_at && $payment->expires_at->isPast();
                $qrisPayload = 'RENMOTE|BOOKING:' . $booking->id . '|INV:' . $payment->invoice_number . '|AMOUNT:' . (int) $payment->amount;
                $qrisImage = 'https://api.qrserver.com/v1/create-qr-code/?size=340x340&data=' . urlencode($qrisPayload);
            @endphp

            <div class="booking-payment-timer {{ $isExpired ? 'is-expired' : '' }}">
                <strong>{{ $isExpired ? 'Waktu pembayaran habis' : 'Sisa waktu pembayaran' }}</strong>
                <span id="paymentCountdown" data-expiry="{{ optional($payment->expires_at)->toIso8601String() }}">
                    {{ $isExpired ? '00:00:00' : '--:--:--' }}
                </span>
            </div>

            <div class="booking-qris-wrap">
                <div class="booking-qris-brand">
                    <img src="{{ $qrisLogoUrl }}" alt="QRIS" loading="lazy">
                    <div>
                        <p>Metode Pembayaran</p>
                        <strong>QRIS</strong>
                    </div>
                </div>
                <img src="{{ $qrisImage }}" alt="QRIS Booking {{ $booking->id }}" class="booking-qris-image">
            </div>

            <div class="booking-payment-meta">
                <div><span>No. Invoice</span><strong>{{ $payment->invoice_number }}</strong></div>
                <div><span>No. Booking</span><strong>#{{ $booking->id }}</strong></div>
                <div><span>Nominal DP</span><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
                <div><span>Status Bukti Bayar</span><strong>{{ $payment->proof_status === 'uploaded' ? 'Sudah diunggah' : 'Belum diunggah' }}</strong></div>
            </div>

            <form action="{{ route('user.bookings.payment.confirm', $booking) }}" method="POST" class="booking-payment-actions">
                @csrf
                <button type="submit" class="booking-btn-primary booking-btn-block" {{ $isExpired ? 'disabled' : '' }}>
                    Konfirmasi Sudah Bayar
                </button>
            </form>
        </article>

        <aside class="booking-checkout-card booking-payment-side">
            <h3>Ringkasan Pesanan</h3>
            <div class="booking-side-vehicle">
                @if($booking->vehicle->image)
                    <img src="{{ Storage::url($booking->vehicle->image) }}" alt="{{ $booking->vehicle->name }}">
                @endif
                <div>
                    <strong>{{ $booking->vehicle->name }}</strong>
                    <p>{{ $booking->vehicle->vendor->store_name }}</p>
                </div>
            </div>

            <div class="booking-price-breakdown">
                <div><span>Total Sewa</span><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></div>
                <div><span>DP Dibayar</span><strong class="booking-value-primary">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</strong></div>
                <div><span>Sisa Pembayaran</span><strong>Rp {{ number_format(max(0, $booking->total_price - $booking->payment->amount), 0, ',', '.') }}</strong></div>
            </div>

            <div class="booking-payment-note-box">
                <h4>Catatan</h4>
                <ul>
                    <li>Pembayaran diverifikasi manual oleh admin/vendor.</li>
                    <li>Setelah bayar, klik tombol konfirmasi lalu unggah bukti pembayaran.</li>
                    <li>Jika waktu habis, hubungi admin untuk dibuatkan invoice baru.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const countdownEl = document.getElementById('paymentCountdown');

    function startCountdown() {
        if (!countdownEl) return;

        const expiryRaw = countdownEl.dataset.expiry;
        if (!expiryRaw) {
            countdownEl.textContent = 'Tidak tersedia';
            return;
        }

        const expiryDate = new Date(expiryRaw);

        function render() {
            const now = new Date();
            const diff = expiryDate.getTime() - now.getTime();

            if (diff <= 0) {
                countdownEl.textContent = '00:00:00';
                return;
            }

            const totalSeconds = Math.floor(diff / 1000);
            const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            const seconds = String(totalSeconds % 60).padStart(2, '0');
            countdownEl.textContent = `${hours}:${minutes}:${seconds}`;

            requestAnimationFrame(() => setTimeout(render, 1000));
        }

        render();
    }

    startCountdown();
</script>
@endpush
