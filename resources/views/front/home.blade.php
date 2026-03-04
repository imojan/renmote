@extends('layouts.front')

@section('title', 'Platform Sewa Motor Terpercaya di Kota Malang')

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero" id="heroSection">
    <div class="hero-bg" id="heroBg" style="background-image: url('{{ asset('images/malang-1.png') }}');"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Platform Sewa Motor <span>Terpercaya</span><br>di Kota Malang</h1>
        <p>
            Pilih dari 100+ motor terbaik &nbsp;&bull;&nbsp; Harga mulai Rp 50.000/hari &bull;<br>
            Booking mudah, cepat dan aman
        </p>
    </div>
    <div class="hero-dots">
        <div class="hero-dot active" onclick="changeSlide(0)"></div>
        <div class="hero-dot" onclick="changeSlide(1)"></div>
    </div>
</section>

{{-- ===== SEARCH WIDGET ===== --}}
<div class="search-widget-wrapper">
    <div class="search-widget">
        <div class="search-tabs">
            <div class="search-tab active">
                <i class="fa fa-motorcycle"></i> Cari Motor
            </div>
        </div>
        <form class="search-fields" method="GET" action="{{ route('search') }}">
            {{-- Lokasi --}}
            <div class="field-group">
                <label><i class="fa fa-map-marker-alt"></i> Lokasi</label>
                <div class="custom-dropdown" data-name="district_id">
                    <input type="hidden" name="district_id" value="">
                    <div class="custom-dropdown-trigger">
                        <i class="fa fa-location-dot field-icon"></i>
                        <span class="custom-dropdown-text">Pilih Lokasi Terdekat</span>
                        <i class="fa fa-chevron-down chevron"></i>
                    </div>
                    <div class="custom-dropdown-menu">
                        <div class="custom-dropdown-item selected" data-value="">Pilih Lokasi Terdekat</div>
                        @foreach($districts as $district)
                            <div class="custom-dropdown-item" data-value="{{ $district->id }}">{{ $district->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tanggal Mulai & Tanggal Selesai --}}
            <div class="field-group-dates">
                <label>
                    <i class="fa fa-calendar-days"></i> Tanggal Sewa
                </label>
                <div class="field-dates-row">
                    <div class="field-select">
                        <i class="fa fa-calendar-days field-icon"></i>
                        <input type="date" name="start_date">
                    </div>
                    <div class="field-select">
                        <i class="fa fa-calendar-days field-icon"></i>
                        <input type="date" name="end_date">
                    </div>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="field-group">
                <label><i class="fa fa-th-large"></i> Kategori</label>
                <div class="custom-dropdown" data-name="category">
                    <input type="hidden" name="category" value="">
                    <div class="custom-dropdown-trigger">
                        <i class="fa fa-th-large field-icon"></i>
                        <span class="custom-dropdown-text">Semua Tipe</span>
                        <i class="fa fa-chevron-down chevron"></i>
                    </div>
                    <div class="custom-dropdown-menu">
                        <div class="custom-dropdown-item selected" data-value="">Semua Tipe</div>
                        <div class="custom-dropdown-item" data-value="matic">Matic</div>
                        <div class="custom-dropdown-item" data-value="bebek">Bebek</div>
                        <div class="custom-dropdown-item" data-value="sport">Sport</div>
                        <div class="custom-dropdown-item" data-value="trail">Trail</div>
                        <div class="custom-dropdown-item" data-value="skutik_premium">Skutik Premium</div>
                        <div class="custom-dropdown-item" data-value="bigbike">Bigbike</div>
                    </div>
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
            heroBg.style.opacity = '1';
        }, 400);
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
        });
    }

    setInterval(() => {
        changeSlide((currentSlide + 1) % slides.length);
    }, 5000);

    // Custom Dropdowns
    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const trigger = dropdown.querySelector('.custom-dropdown-trigger');
        const menu = dropdown.querySelector('.custom-dropdown-menu');
        const hiddenInput = dropdown.querySelector('input[type="hidden"]');
        const textEl = dropdown.querySelector('.custom-dropdown-text');

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            // Close all other dropdowns
            document.querySelectorAll('.custom-dropdown.open').forEach(d => {
                if (d !== dropdown) d.classList.remove('open');
            });
            dropdown.classList.toggle('open');
        });

        menu.querySelectorAll('.custom-dropdown-item').forEach(item => {
            item.addEventListener('click', () => {
                // Update selection
                menu.querySelectorAll('.custom-dropdown-item').forEach(i => i.classList.remove('selected'));
                item.classList.add('selected');
                hiddenInput.value = item.dataset.value;
                textEl.textContent = item.textContent;
                dropdown.classList.remove('open');
            });
        });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-dropdown.open').forEach(d => d.classList.remove('open'));
    });
</script>
@endpush
