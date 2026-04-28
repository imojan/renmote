@extends('layouts.front')

@section('title', 'Detail Booking')

@section('content')
<section class="section booking-front-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Detail Booking</h2>
            <p class="booking-front-subtitle">Ringkasan pesanan dan status pembayaran kendaraanmu.</p>
        </div>
        <a href="{{ route('user.bookings.index') }}" class="booking-back-link">
            <i class="fa fa-arrow-left"></i> Kembali ke riwayat booking
        </a>
    </div>

    @if(session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="booking-alert booking-alert-error">{{ session('error') }}</div>
    @endif

    <article class="booking-detail-card">
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

        <div class="booking-detail-grid">
            <div>
                <label>Tanggal Mulai</label>
                <strong>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</strong>
            </div>
            <div>
                <label>Tanggal Selesai</label>
                <strong>{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</strong>
            </div>
            <div>
                <label>Total Harga</label>
                <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
            </div>
            <div>
                <label>Metode Pengambilan</label>
                <strong>{{ $booking->fulfillment_method === 'delivery' ? 'Diantar ke alamat user' : 'Ambil di outlet/vendor' }}</strong>
            </div>
            @if($booking->payment)
                <div>
                    <label>Status Pembayaran</label>
                    <strong>{{ strtoupper($booking->payment->payment_type) }} - {{ ucfirst($booking->payment->status) }}</strong>
                </div>
            @endif
        </div>

        @if($booking->fulfillment_method === 'delivery')
            @php
                $deliveryAddress = $booking->delivery_address_snapshot ?? [];
            @endphp
            <div class="booking-payment-info">
                <h4>Alamat Pengantaran</h4>
                <ul>
                    <li>{{ $deliveryAddress['label'] ?? optional($booking->address)->label ?? '-' }} ({{ (($deliveryAddress['address_type'] ?? optional($booking->address)->address_type) === 'temporary') ? 'Sementara' : 'Tetap' }})</li>
                    <li>{{ $deliveryAddress['street'] ?? optional($booking->address)->street ?? '-' }}, {{ $deliveryAddress['district'] ?? optional(optional($booking->address)->district)->name ?? '-' }}, {{ $deliveryAddress['city'] ?? optional($booking->address)->city ?? '-' }} {{ $deliveryAddress['postal_code'] ?? optional($booking->address)->postal_code ?? '-' }}</li>
                </ul>
            </div>
        @endif

        @if($booking->payment)
            @php
                $proofStatusLabel = match ($booking->payment->proof_status) {
                    'uploaded' => 'Menunggu verifikasi',
                    'verified' => 'Terverifikasi',
                    'rejected' => 'Ditolak (unggah ulang diperlukan)',
                    default => ucfirst(str_replace('_', ' ', $booking->payment->proof_status ?? 'not_uploaded')),
                };
            @endphp
            <div class="booking-payment-summary">
                <h4>Informasi Pembayaran</h4>
                <ul>
                    <li>Invoice: <strong>{{ $booking->payment->invoice_number ?: '-' }}</strong></li>
                    <li>Metode: <strong>{{ strtoupper($booking->payment->payment_method ?? 'qris') }}</strong></li>
                    <li>Nominal dibayar: <strong>Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</strong></li>
                    <li>Status bukti pembayaran: <strong>{{ $proofStatusLabel }}</strong></li>
                    @if($booking->payment->proof_review_notes)
                        <li>Catatan reviewer: <strong>{{ $booking->payment->proof_review_notes }}</strong></li>
                    @endif
                    @if($booking->payment->payment_type === 'dp')
                        <li>Sisa pembayaran saat pengambilan: <strong>Rp {{ number_format($booking->total_price - $booking->payment->amount, 0, ',', '.') }}</strong></li>
                    @endif
                </ul>
            </div>
        @endif

        @if($booking->payment)
            <div class="booking-form-actions">
                @if(!$booking->payment->proof_path)
                    <a href="{{ route('user.bookings.payment', $booking) }}" class="booking-btn-primary">Lanjutkan Flow Pembayaran</a>
                @else
                    <a href="{{ route('user.bookings.invoice', $booking) }}" class="booking-btn-primary">Lihat Invoice Selesai</a>
                    <a href="{{ route('user.bookings.invoice.download', $booking) }}" class="booking-btn-secondary">Download Invoice PDF</a>
                    <a href="{{ route('documents.payment.proof.media', $booking->payment) }}" target="_blank" class="booking-btn-secondary">Lihat Bukti Bayar</a>
                @endif
            </div>
        @endif

        @if($booking->status === 'pending')
            <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST"
                data-confirm-title="Batalkan booking?"
                data-confirm-message="Pesanan ini akan dibatalkan. Lanjutkan?"
                data-confirm-confirm-text="Ya, Batalkan"
                data-confirm-cancel-text="Tidak">
                @csrf
                <button type="submit" class="booking-btn-danger booking-btn-block">Batalkan Booking</button>
            </form>
        @endif
    </article>
</section>
@endsection
