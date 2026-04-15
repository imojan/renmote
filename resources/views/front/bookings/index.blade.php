@extends('layouts.front')

@section('title', 'Riwayat Booking Saya')

@section('content')
<section class="section booking-front-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Riwayat Booking Saya</h2>
            <p class="booking-front-subtitle">Semua pesanan yang pernah kamu lakukan ada di sini.</p>
        </div>
        <a href="{{ route('search') }}" class="booking-btn-primary booking-inline-btn">Booking Kendaraan Baru</a>
    </div>

    @if(session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="booking-alert booking-alert-error">{{ session('error') }}</div>
    @endif

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
                                data-confirm-message="Pesanan ini akan dibatalkan. Lanjutkan?"
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
        <div class="booking-empty-state">
            <p>Belum ada booking. Yuk cari kendaraan yang cocok untuk perjalananmu.</p>
            <a href="{{ route('search') }}" class="booking-btn-primary booking-inline-btn">Cari Kendaraan</a>
        </div>
    @endif
</section>
@endsection
