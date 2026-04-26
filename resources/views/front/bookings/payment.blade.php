@extends('layouts.front')

@section('title', 'Pembayaran Booking')

@section('content')
<section class="section booking-front-section booking-checkout-section">
    @php
        $payment = $booking->payment;
        $isExpired = $payment->expires_at && $payment->expires_at->isPast();
        $isPaid = $payment->status === 'paid';
        $gatewayStatus = $payment->gateway_status ?? 'pending';
        $isFailed = !$isPaid && ($gatewayStatus === 'failed' || $isExpired);
        $proofStatusLabel = match ($payment->proof_status) {
            'uploaded' => 'Sudah diunggah (menunggu verifikasi)',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak (unggah ulang diperlukan)',
            default => 'Belum diunggah',
        };
        $gatewayStatusLabel = match ($gatewayStatus) {
            'paid' => 'Berhasil',
            'failed' => 'Gagal / Kadaluarsa',
            default => 'Pending',
        };
    @endphp

    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Pembayaran DP</h2>
            <p class="booking-front-subtitle">Selesaikan pembayaran via Midtrans Sandbox untuk melanjutkan ke upload bukti pembayaran.</p>
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

    @if(session('info'))
        <div class="booking-alert booking-alert-info">{{ session('info') }}</div>
    @endif

    <div class="booking-payment-layout">
        <article class="booking-checkout-card">
            <header class="booking-checkout-card-head">
                <h3>Pembayaran Midtrans (Sandbox)</h3>
                <p>Gunakan widget pembayaran di bawah ini. Metode tersedia: QRIS, e-wallet, virtual account, dan kartu.</p>
            </header>

            <div class="booking-payment-timer {{ $isExpired ? 'is-expired' : '' }}">
                <strong>{{ $isExpired ? 'Waktu pembayaran habis' : 'Sisa waktu pembayaran' }}</strong>
                <span id="paymentCountdown" data-expiry="{{ optional($payment->expires_at)->toIso8601String() }}">
                    {{ $isExpired ? '00:00:00' : '--:--:--' }}
                </span>
            </div>

            @if($midtransError)
                <div class="booking-alert booking-alert-error mt-3">{{ $midtransError }}</div>
            @endif

            @if($isPaid)
                <div class="booking-midtrans-state booking-midtrans-state-success">
                    <h4>Pembayaran Berhasil</h4>
                    <p>Status transaksi sudah terverifikasi. Lanjutkan ke upload bukti pembayaran.</p>
                    <a href="{{ route('user.bookings.payment.proof', $booking) }}" class="booking-btn-primary booking-btn-block">Lanjut Upload Bukti Pembayaran</a>
                </div>
            @elseif($isFailed)
                <div class="booking-midtrans-state booking-midtrans-state-failed">
                    <h4>Pembayaran Gagal / Kadaluarsa</h4>
                    <p>Invoice ini sudah tidak valid. Buat invoice baru untuk mencoba pembayaran kembali.</p>

                    @if($canRetryPayment)
                        <form action="{{ route('user.bookings.payment.retry', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="booking-btn-primary booking-btn-block">Ajukan Ulang Pembayaran</button>
                        </form>
                    @endif
                </div>
            @elseif($snapToken)
                <div class="booking-midtrans-wrap">
                    <div id="midtransEmbedContainer" class="booking-midtrans-embed"></div>
                </div>

                <form action="{{ route('user.bookings.payment.confirm', $booking) }}" method="POST" class="booking-payment-actions">
                    @csrf
                    <button type="submit" class="booking-btn-primary booking-btn-block">
                        Saya Sudah Bayar, Cek Status
                    </button>
                </form>
            @else
                <div class="booking-midtrans-state booking-midtrans-state-pending">
                    <h4>Widget Pembayaran Belum Tersedia</h4>
                    <p>Silakan refresh halaman ini atau ajukan ulang pembayaran.</p>
                </div>
            @endif

            <div class="booking-payment-meta">
                <div><span>No. Invoice</span><strong>{{ $payment->invoice_number }}</strong></div>
                <div><span>No. Booking</span><strong>#{{ $booking->id }}</strong></div>
                <div><span>Nominal DP</span><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
                <div><span>Status Transaksi</span><strong>{{ $gatewayStatusLabel }}</strong></div>
                <div><span>Status Bukti Bayar</span><strong>{{ $proofStatusLabel }}</strong></div>
            </div>
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
                    <li>Widget Midtrans akan menyesuaikan bahasa Indonesia/Inggris sesuai locale aplikasi.</li>
                    <li>Jika pembayaran sukses, sistem otomatis mengarahkan ke langkah upload bukti pembayaran.</li>
                    <li>Jika transaksi gagal atau kadaluarsa, ajukan ulang pembayaran untuk membuat invoice baru.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>
@endsection

@push('scripts')
    @if(!$midtransError && !$isPaid && !$isFailed && $snapToken)
        <script src="{{ $midtransSnapScriptUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif

<script>
    const countdownEl = document.getElementById('paymentCountdown');
    const snapToken = @json($snapToken);
    const midtransLanguage = @json($midtransLanguage);
    const midtransStatusEndpoint = @json($midtransStatusEndpoint);
    const csrfToken = @json(csrf_token());

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

    async function syncMidtransResult(result) {
        if (!midtransStatusEndpoint || !result || !result.order_id) {
            return;
        }

        try {
            const response = await fetch(midtransStatusEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    order_id: result.order_id,
                    transaction_status: result.transaction_status || null,
                    fraud_status: result.fraud_status || null,
                    payment_type: result.payment_type || null,
                    transaction_id: result.transaction_id || null,
                }),
            });

            const payload = await response.json();

            if (payload.next_url) {
                window.location.href = payload.next_url;
                return;
            }
        } catch (error) {
            // Fallback sederhana agar user tetap bisa lanjut manual check status.
        }

        window.location.reload();
    }

    function mountMidtransEmbed() {
        if (!snapToken || !window.snap) {
            return;
        }

        window.snap.embed(snapToken, {
            embedId: 'midtransEmbedContainer',
            language: midtransLanguage,
            onSuccess: syncMidtransResult,
            onPending: syncMidtransResult,
            onError: syncMidtransResult,
            onClose: function () {
                // User menutup widget sebelum transaksi selesai.
            },
        });
    }

    startCountdown();
    mountMidtransEmbed();
</script>
@endpush
