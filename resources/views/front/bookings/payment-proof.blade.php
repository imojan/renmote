@extends('layouts.front')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<section class="section booking-front-section booking-checkout-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Upload Bukti Pembayaran</h2>
            <p class="booking-front-subtitle">Unggah screenshot/foto/PDF bukti transfer agar proses verifikasi lebih cepat dan transparan.</p>
        </div>
        <a href="{{ route('user.bookings.payment', $booking) }}" class="booking-back-link">
            <i class="fa fa-arrow-left"></i> Kembali ke halaman bayar
        </a>
    </div>

    @include('front.bookings.partials.checkout-steps', ['currentStep' => 3])

    @if(session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="booking-alert booking-alert-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="booking-alert booking-alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="booking-payment-layout">
        <article class="booking-checkout-card">
            <header class="booking-checkout-card-head">
                <h3>Unggah Bukti Pembayaran</h3>
                <p>Format JPG/PNG/PDF, maksimal 6MB. Pastikan nominal dan waktu transaksi terlihat jelas.</p>
            </header>

            <form action="{{ route('user.bookings.payment.proof.store', $booking) }}" method="POST" enctype="multipart/form-data" class="booking-proof-form">
                @csrf

                <label class="booking-label">File Bukti Pembayaran <span class="booking-required">*</span></label>
                <input type="file" name="payment_proof" class="booking-input-file" accept=".jpg,.jpeg,.png,.pdf" required>

                <label class="booking-label">Catatan Tambahan (opsional)</label>
                <textarea name="proof_notes" rows="3" class="booking-textarea" placeholder="Contoh: pembayaran lewat mobile banking BCA atas nama ...">{{ old('proof_notes', $booking->payment->proof_notes) }}</textarea>

                @if($booking->payment->proof_path)
                    <div class="booking-proof-existing">
                        <strong>Bukti saat ini:</strong>
                        <a href="{{ route('documents.payment.proof.media', $booking->payment) }}" target="_blank" class="booking-inline-link">
                            Lihat bukti pembayaran yang terunggah
                        </a>
                    </div>
                @endif

                <button type="submit" class="booking-btn-primary booking-btn-block">Kirim Bukti Pembayaran</button>
            </form>
        </article>

        <aside class="booking-checkout-card booking-payment-side">
            <h3>Ringkasan Verifikasi</h3>
            <div class="booking-payment-meta">
                <div><span>No. Booking</span><strong>#{{ $booking->id }}</strong></div>
                <div><span>No. Invoice</span><strong>{{ $booking->payment->invoice_number }}</strong></div>
                <div><span>Metode</span><strong>{{ strtoupper($booking->payment->payment_method) }}</strong></div>
                <div><span>Nominal DP</span><strong>Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</strong></div>
                <div><span>Status Bukti</span><strong>{{ $booking->payment->proof_status === 'uploaded' ? 'Sudah diunggah' : 'Belum diunggah' }}</strong></div>
            </div>

            <div class="booking-payment-note-box">
                <h4>Setelah Upload</h4>
                <ul>
                    <li>Admin Renmote dan vendor akan melihat bukti pembayaran ini.</li>
                    <li>Status pesanan tetap diproses sampai verifikasi selesai.</li>
                    <li>Kamu tetap bisa melihat detail bukti di halaman invoice.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>
@endsection
