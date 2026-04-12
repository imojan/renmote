@extends('layouts.front')

@section('title', 'Wishlist Saya')

@section('content')
<section class="section booking-front-section">
    <div class="booking-front-head">
        <div>
            <h2 class="section-title">Wishlist Saya</h2>
            <p class="booking-front-subtitle">Simpan kendaraan favorit agar lebih cepat saat akan booking berikutnya.</p>
        </div>
        <a href="{{ route('search') }}" class="booking-btn-primary booking-inline-btn">Cari Kendaraan</a>
    </div>

    <div class="booking-empty-state">
        <p>Belum ada kendaraan di wishlist kamu.</p>
        <a href="{{ route('search') }}" class="booking-btn-secondary">Jelajahi Kendaraan</a>
    </div>
</section>
@endsection
