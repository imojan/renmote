@extends('layouts.front')

@section('title', 'Platform Sewa Motor Terpercaya di Kota Malang')

@push('styles')
<style>
    /* ===== HERO ===== */
    .hero {
        position: relative;
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 40%, #1976D2 70%, #2196F3 100%);
        min-height: 420px;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
    .hero-bg {
        position: absolute;
        inset: 0;
        opacity: 0.25;
        background-size: cover;
        background-position: center;
        transition: opacity 0.8s ease;
    }
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(13,71,161,0.92) 0%, rgba(13,71,161,0.6) 60%, rgba(13,71,161,0.2) 100%);
    }
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px 100px;
        color: #fff;
    }
    .hero h1 {
        font-size: clamp(28px, 4vw, 48px);
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 16px;
    }
    .hero h1 span { color: #FF6B00; }
    .hero p {
        font-size: 15px;
        opacity: .88;
        line-height: 1.6;
    }
    .hero-dots {
        display: flex;
        gap: 6px;
        margin-top: 20px;
    }
    .hero-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        cursor: pointer;
        transition: all 0.3s;
    }
    .hero-dot.active { background: #fff; width: 24px; border-radius: 4px; }

    /* ===== SEARCH WIDGET ===== */
    .search-widget {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,.15);
        padding: 0;
        max-width: 1160px;
        margin: 0 auto;
        transform: translateY(-28px);
        position: relative;
        z-index: 10;
        overflow: hidden;
    }
    .search-tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
    }
    .search-tab {
        padding: 14px 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        margin-bottom: -1px;
    }
    .search-tab.active {
        border-bottom-color: var(--primary);
        color: var(--primary);
    }
    .search-fields {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 12px;
        padding: 20px 24px;
        align-items: end;
    }
    .field-group label {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .field-select {
        display: flex;
        align-items: center;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 0;
        gap: 0;
        cursor: pointer;
        background: var(--bg);
        transition: border-color .2s;
        overflow: hidden;
    }
    .field-select:hover { border-color: var(--primary); }
    .field-select i.field-icon { color: var(--primary); font-size: 16px; padding-left: 14px; }
    .field-select select,
    .field-select input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--muted);
        background: transparent;
        font-family: inherit;
        cursor: pointer;
        -webkit-appearance: none;
        appearance: none;
    }
    .field-select .chevron { color: var(--muted); font-size: 12px; padding-right: 14px; }
    .btn-search {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: background .2s;
        height: 46px;
        align-self: end;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-search:hover { background: var(--primary-dark); }

    /* ===== SECTION COMMON ===== */
    .section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 8px 20px 40px;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 20px;
        font-weight: 800;
    }
    .see-all {
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }
    .see-all:hover { text-decoration: underline; }

    /* ===== KATEGORI ===== */
    .kategori-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
    }
    .kategori-card {
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 20px 10px;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .kategori-card:hover {
        border-color: var(--primary);
        background: #EEF2FF;
        transform: translateY(-2px);
    }
    .kategori-card img {
        width: 72px;
        height: 52px;
        object-fit: contain;
        margin: 0 auto 10px;
        display: block;
    }
    .kategori-icon {
        width: 72px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 26px;
        color: var(--primary);
    }
    .kategori-card span {
        font-size: 13px;
        font-weight: 600;
        display: block;
    }

    /* ===== MOTOR CARDS ===== */
    .motor-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
    }
    .motor-card {
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        transition: all .25s;
        cursor: pointer;
    }
    .motor-card:hover {
        box-shadow: 0 8px 24px rgba(21,101,192,.12);
        transform: translateY(-3px);
        border-color: var(--primary);
    }
    .motor-img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        background: linear-gradient(135deg, #e8f4fd 0%, #b3d9f7 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .motor-img img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }
    .motor-body { padding: 12px; }
    .motor-name { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
    .motor-price {
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 4px;
    }
    .motor-price strong {
        color: var(--primary);
        font-size: 14px;
        font-weight: 800;
    }
    .motor-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 4px;
    }
    .motor-rating i { color: #F59E0B; }
    .motor-type {
        font-size: 11px;
        color: var(--muted);
        margin-bottom: 10px;
    }
    .btn-lihat {
        display: block;
        background: var(--primary);
        color: #fff;
        text-align: center;
        padding: 8px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-lihat:hover { background: var(--primary-dark); }

    /* ===== VENDOR ===== */
    .vendor-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .vendor-card {
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 18px;
        transition: all .25s;
    }
    .vendor-card:hover {
        box-shadow: 0 8px 24px rgba(21,101,192,.1);
        border-color: var(--primary);
    }
    .vendor-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .vendor-logo {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: #FEE2E2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        color: #DC2626;
        flex-shrink: 0;
    }
    .vendor-name { font-size: 15px; font-weight: 700; }
    .vendor-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 14px;
    }
    .vendor-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--muted);
    }
    .vendor-meta-row i { color: var(--primary); width: 14px; }
    .vendor-meta-row i.fa-star { color: #F59E0B; }
    .vendor-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-fav {
        width: 36px; height: 36px;
        border: 1.5px solid var(--border);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--muted);
        background: #fff;
        transition: all .2s;
        flex-shrink: 0;
    }
    .btn-fav:hover { border-color: #EF4444; color: #EF4444; }
    .btn-chat {
        flex: 1;
        background: var(--green);
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background .2s;
        text-decoration: none;
    }
    .btn-chat:hover { background: #16a34a; }
    .btn-detail {
        flex: 1;
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background .2s;
        text-decoration: none;
    }
    .btn-detail:hover { background: var(--primary-dark); }

    /* ===== ARTIKEL ===== */
    .artikel-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .artikel-card {
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        transition: all .25s;
    }
    .artikel-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,.1);
        transform: translateY(-2px);
    }
    .artikel-img {
        position: relative;
        height: 170px;
        overflow: hidden;
    }
    .artikel-img img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .3s;
    }
    .artikel-card:hover .artikel-img img { transform: scale(1.04); }
    .artikel-badge {
        position: absolute;
        top: 10px; left: 10px;
        background: var(--accent);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 5px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .artikel-body { padding: 14px; }
    .artikel-date { font-size: 11px; color: var(--muted); margin-bottom: 6px; }
    .artikel-title { font-size: 13px; font-weight: 700; margin-bottom: 6px; line-height: 1.4; }
    .artikel-excerpt { font-size: 12px; color: var(--muted); line-height: 1.5; margin-bottom: 10px; }
    .artikel-read {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: gap .2s;
    }
    .artikel-read:hover { gap: 8px; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .motor-grid { grid-template-columns: repeat(4, 1fr); }
        .kategori-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .search-fields { grid-template-columns: 1fr; }
        .motor-grid { grid-template-columns: repeat(2, 1fr); }
        .vendor-grid, .artikel-grid { grid-template-columns: 1fr; }
        .kategori-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero" id="heroSection">
    <div class="hero-bg" id="heroBg" style="background-image: url('{{ asset('images/malang-1.png') }}');"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Platform Sewa Motor <span>Terpercaya</span><br>di Kota Malang</h1>
        <p>
            Pilih dari 100+ motor terbaik &nbsp;&bull;&nbsp; Harga mulai Rp 50.000/hari<br>
            Booking mudah, cepat dan aman
        </p>
        <div class="hero-dots">
            <div class="hero-dot active" onclick="changeSlide(0)"></div>
            <div class="hero-dot" onclick="changeSlide(1)"></div>
        </div>
    </div>
</section>

{{-- ===== SEARCH WIDGET ===== --}}
<div style="background: var(--bg); padding-bottom: 20px;">
    <div class="search-widget" style="margin-left: 20px; margin-right: 20px;">
        <div class="search-tabs">
            <div class="search-tab active">
                <i class="fa fa-motorcycle"></i> Cari Motor
            </div>
        </div>
        <form class="search-fields" method="GET" action="{{ route('search') }}">
            <div class="field-group">
                <label><i class="fa fa-map-marker-alt"></i> Lokasi</label>
                <div class="field-select">
                    <i class="fa fa-location-dot field-icon"></i>
                    <select name="district_id">
                        <option value="">Pilih Lokasi Terdekat</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-chevron-down chevron"></i>
                </div>
            </div>
            <div class="field-group">
                <label><i class="fa fa-calendar"></i> Tanggal</label>
                <div class="field-select">
                    <i class="fa fa-calendar-alt field-icon"></i>
                    <input type="date" name="start_date" placeholder="Pilih Tanggal">
                    <i class="fa fa-chevron-down chevron"></i>
                </div>
            </div>
            <div class="field-group">
                <label><i class="fa fa-th"></i> Kategori</label>
                <div class="field-select">
                    <i class="fa fa-th-large field-icon"></i>
                    <select name="category">
                        <option value="">Semua Tipe</option>
                        <option value="matic">Matic</option>
                        <option value="bebek">Bebek</option>
                        <option value="sport">Sport</option>
                        <option value="trail">Trail</option>
                        <option value="skutik_premium">Skutik Premium</option>
                        <option value="bigbike">Bigbike</option>
                    </select>
                    <i class="fa fa-chevron-down chevron"></i>
                </div>
            </div>
            <button type="submit" class="btn-search">
                <i class="fa fa-search"></i> Cari Motor
            </button>
        </form>
    </div>
</div>

{{-- ===== KATEGORI ===== --}}
<div class="section">
    <div class="section-header">
        <h2 class="section-title">Kategori</h2>
    </div>
    <div class="kategori-grid">
        @php
        $categories = [
            ['label' => 'Matic',          'slug' => 'matic',          'image' => 'matic.png',           'color' => '#EEF2FF'],
            ['label' => 'Bebek',          'slug' => 'bebek',          'image' => 'bebek.png',           'color' => '#F0FDF4'],
            ['label' => 'Sport',          'slug' => 'sport',          'image' => 'sport.png',           'color' => '#FFF7ED'],
            ['label' => 'Trail',          'slug' => 'trail',          'image' => 'trail.png',           'color' => '#FEF2F2'],
            ['label' => 'Skutik Premium', 'slug' => 'skutik_premium', 'image' => 'skutik-premium.png',  'color' => '#F8FAFC'],
            ['label' => 'Bigbike',        'slug' => 'bigbike',        'image' => 'bigbike.png',         'color' => '#FDF4FF'],
        ];
        @endphp
        @foreach($categories as $cat)
        <a href="{{ route('search', ['category' => $cat['slug']]) }}" class="kategori-card">
            <img src="{{ asset('images/' . $cat['image']) }}" alt="{{ $cat['label'] }}"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="kategori-icon" style="display:none; background:{{ $cat['color'] }};">
                <i class="fa fa-motorcycle"></i>
            </div>
            <span>{{ $cat['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>

{{-- ===== MOTOR POPULER ===== --}}
<div class="section">
    <div class="section-header">
        <h2 class="section-title">Motor Populer</h2>
        <a href="{{ route('search') }}" class="see-all">Lihat Semua <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="motor-grid">
        @forelse($popularVehicles as $vehicle)
        <div class="motor-card">
            <div class="motor-img">
                @if($vehicle->image)
                    <img src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->name }}">
                @else
                    <i class="fa fa-motorcycle" style="font-size: 48px; color: #90caf9;"></i>
                @endif
            </div>
            <div class="motor-body">
                <div class="motor-name">{{ $vehicle->name }}</div>
                <div class="motor-price">mulai <strong>Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</strong>/hari</div>
                <div class="motor-rating">
                    <i class="fa fa-star"></i>
                    <strong>4.8</strong>
                    <span>({{ rand(50, 200) }})</span>
                </div>
                <div class="motor-type">{{ ucfirst(str_replace('_', ' ', $vehicle->category)) }} &bull; {{ $vehicle->year }}cc</div>
                <a href="{{ route('search', ['keyword' => $vehicle->name]) }}" class="btn-lihat">Lihat Pilihan</a>
            </div>
        </div>
        @empty
        @for($i = 0; $i < 5; $i++)
        <div class="motor-card">
            <div class="motor-img" style="background: linear-gradient(135deg, #e8f0fe, #c3d7fb);">
                <i class="fa fa-motorcycle" style="font-size: 48px; color: #90caf9;"></i>
            </div>
            <div class="motor-body">
                <div class="motor-name">Yamaha NMAX 155</div>
                <div class="motor-price">mulai <strong>Rp 120.000</strong>/hari</div>
                <div class="motor-rating">
                    <i class="fa fa-star"></i>
                    <strong>4.8</strong>
                    <span>(120)</span>
                </div>
                <div class="motor-type">Skuter Premium &bull; 155cc</div>
                <a href="#" class="btn-lihat">Lihat Pilihan</a>
            </div>
        </div>
        @endfor
        @endforelse
    </div>
</div>

{{-- ===== VENDOR ANDALAN ===== --}}
<div class="section">
    <div class="section-header">
        <h2 class="section-title">Vendor Andalan</h2>
        <a href="{{ route('search') }}" class="see-all">Lihat Semua <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="vendor-grid">
        @forelse($vendors as $vendor)
        <div class="vendor-card">
            <div class="vendor-header">
                <div class="vendor-logo">
                    {{ strtoupper(substr($vendor->store_name, 0, 2)) }}
                </div>
                <div>
                    <div class="vendor-name">{{ $vendor->store_name }}</div>
                </div>
            </div>
            <div class="vendor-meta">
                <div class="vendor-meta-row"><i class="fa fa-map-marker-alt"></i> {{ $vendor->district->name ?? 'Kecamatan' }}</div>
                <div class="vendor-meta-row">
                    <i class="fa fa-star"></i> <strong>4.8</strong> ({{ rand(80, 200) }})
                    &nbsp;&bull;&nbsp;
                    <i class="fa fa-motorcycle"></i> {{ $vendor->vehicles->count() }}+ Unit Kendaraan
                </div>
            </div>
            <div class="vendor-actions">
                <button class="btn-fav"><i class="fa fa-heart"></i></button>
                <a href="https://wa.me/{{ $vendor->phone ? preg_replace('/[^0-9]/', '', $vendor->phone) : '6289631926343' }}" target="_blank" class="btn-chat">
                    <i class="fab fa-whatsapp"></i> Chat Vendor
                </a>
                <a href="{{ route('vendors.show', $vendor) }}" class="btn-detail">
                    <i class="fa fa-info-circle"></i> Lihat Lebih Lengkap
                </a>
            </div>
        </div>
        @empty
        @for($i = 0; $i < 3; $i++)
        <div class="vendor-card">
            <div class="vendor-header">
                <div class="vendor-logo">SM</div>
                <div>
                    <div class="vendor-name">Sunday Motor Rent</div>
                </div>
            </div>
            <div class="vendor-meta">
                <div class="vendor-meta-row"><i class="fa fa-map-marker-alt"></i> Kecamatan Belimbing</div>
                <div class="vendor-meta-row"><i class="fa fa-star"></i> <strong>4.8</strong> (120) &nbsp;&bull;&nbsp; <i class="fa fa-motorcycle"></i> 12+ Unit Kendaraan</div>
            </div>
            <div class="vendor-actions">
                <button class="btn-fav"><i class="fa fa-heart"></i></button>
                <button class="btn-chat"><i class="fab fa-whatsapp"></i> Chat Vendor</button>
                <button class="btn-detail"><i class="fa fa-info-circle"></i> Lihat Lebih Lengkap</button>
            </div>
        </div>
        @endfor
        @endforelse
    </div>
</div>

{{-- ===== ARTIKEL & REKOMENDASI ===== --}}
<div class="section">
    <div class="section-header">
        <h2 class="section-title">Artikel dan Rekomendasi</h2>
        <a href="#" class="see-all">Lihat Semua <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="artikel-grid">
        @php
        $demoArticles = [
            ['title' => '10 Rekomendasi Trip Tempat Wisata Terbaik Di Malang Dan Sekitarnya!', 'date' => '12 November 2025 20:08', 'desc' => 'Jakarta (ANTARA) – Gabungan Industri Kendaraan Bermotor Indonesia (Gaikindo) Telah Mengeluarkan Rekapan Data Penjualan Untuk Bulan Oktober 2025...'],
            ['title' => '10 Rekomendasi Trip Tempat Wisata Terbaik Di Malang Dan Sekitarnya!', 'date' => '12 November 2025 21:58', 'desc' => 'Jakarta (ANTARA) – Gabungan Industri Kendaraan Bermotor Indonesia (Gaikindo) Telah Mengeluarkan Rekapan Data Penjualan Untuk Bulan Oktober 2025...'],
            ['title' => 'Penjualan Wholesales Isuzu MU-X Tercatat Nihil Pada Oktober 2025', 'date' => '12 November 2025 20:05', 'desc' => 'Jakarta (ANTARA) – Gabungan Industri Kendaraan Bermotor Indonesia (Gaikindo) Telah Mengeluarkan Rekapan Data Penjualan Untuk Bulan Oktober 2025...'],
        ];
        @endphp
        @foreach($demoArticles as $article)
        <div class="artikel-card">
            <div class="artikel-img">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80" alt="Artikel">
                <span class="artikel-badge">Rekomendasi Wisata</span>
            </div>
            <div class="artikel-body">
                <div class="artikel-date">{{ $article['date'] }}</div>
                <div class="artikel-title">{{ $article['title'] }}</div>
                <div class="artikel-excerpt">{{ $article['desc'] }}</div>
                <a href="#" class="artikel-read">Lihat Selengkapnya <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Hero Slider
    const slides = [
        '{{ asset("images/malang-1.png") }}',
        '{{ asset("images/malang-2.png") }}'
    ];
    let currentSlide = 0;
    const heroBg = document.getElementById('heroBg');
    const dots = document.querySelectorAll('.hero-dot');

    function changeSlide(index) {
        currentSlide = index;
        heroBg.style.opacity = '0';
        setTimeout(() => {
            heroBg.style.backgroundImage = `url('${slides[currentSlide]}')`;
            heroBg.style.opacity = '0.25';
        }, 400);
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
        });
    }

    setInterval(() => {
        changeSlide((currentSlide + 1) % slides.length);
    }, 5000);
</script>
@endpush
