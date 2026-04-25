@extends('layouts.front')

@section('title', 'Invoice Penyewaan')

@section('content')
<section class="section booking-front-section booking-checkout-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Selesai - Invoice Penyewaan</h2>
            <p class="booking-front-subtitle">Pesanan sudah terekam. Menunggu verifikasi pembayaran oleh admin/vendor.</p>
        </div>
        <a href="{{ route('user.bookings.index') }}" class="booking-back-link">
            <i class="fa fa-arrow-left"></i> Kembali ke riwayat booking
        </a>
    </div>

    @include('front.bookings.partials.checkout-steps', ['currentStep' => 4])

    @if(session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    <div class="booking-invoice-wrap">
        <article class="booking-invoice-card">
            <div class="booking-invoice-icon">
                <i class="fa fa-circle-check"></i>
            </div>
            <h3>Pesanan Berhasil Dibuat</h3>
            <p>Terima kasih. Bukti pembayaran sudah kami terima dan akan diverifikasi oleh admin/vendor.</p>

            <div class="booking-invoice-table">
                <div><span>No. Booking</span><strong>#{{ $booking->id }}</strong></div>
                <div><span>No. Invoice</span><strong>{{ $booking->payment->invoice_number }}</strong></div>
                <div><span>Tanggal Booking</span><strong>{{ $booking->created_at->format('d M Y H:i') }}</strong></div>
                <div><span>Kendaraan</span><strong>{{ $booking->vehicle->name }}</strong></div>
                <div><span>Periode Sewa</span><strong>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</strong></div>
                <div><span>Metode Ambil</span><strong>{{ $booking->fulfillment_method === 'delivery' ? 'Diantar ke alamat user' : 'Ambil di outlet vendor' }}</strong></div>
                <div><span>Metode Bayar</span><strong>{{ strtoupper($booking->payment->payment_method) }}</strong></div>
                <div><span>Total Sewa</span><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></div>
                <div><span>Total DP Dibayar</span><strong class="booking-value-primary">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</strong></div>
                <div><span>Status Bukti Bayar</span><strong>{{ $booking->payment->proof_status === 'uploaded' ? 'Menunggu verifikasi' : ucfirst(str_replace('_', ' ', $booking->payment->proof_status)) }}</strong></div>
            </div>

            @if($booking->fulfillment_method === 'delivery')
                <div class="booking-invoice-address">
                    <h4>Alamat Pengantaran</h4>
                    @php
                        $snapshot = $booking->delivery_address_snapshot ?? [];
                    @endphp
                    <p>
                        {{ $snapshot['label'] ?? '-' }} • {{ ($snapshot['address_type'] ?? 'temporary') === 'temporary' ? 'Sementara' : 'Tetap' }}<br>
                        {{ $snapshot['street'] ?? '-' }}, {{ $snapshot['district'] ?? '-' }}, {{ $snapshot['city'] ?? '-' }} {{ $snapshot['postal_code'] ?? '-' }}
                    </p>
                </div>
            @endif

            <div class="booking-invoice-actions">
                <a href="{{ route('documents.payment.proof.media', $booking->payment) }}" target="_blank" class="booking-btn-secondary booking-btn-block">Lihat Bukti Pembayaran</a>
                <button type="button" class="booking-btn-primary booking-btn-block" onclick="window.print()">Download Receipt (PDF)</button>
                <a href="{{ route('user.bookings.show', $booking->id) }}" class="booking-btn-secondary booking-btn-block">Lihat Detail Pesanan</a>
            </div>
        </article>
    </div>
</section>
@endsection
