@extends('layouts.front')

@section('title', 'Platform Sewa Motor Terpercaya di Kota Malang')

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero" id="heroSection">
    <div class="hero-track" id="heroTrack">
        @php
            $heroImages = [
                asset('images/malang-1.png'),
                asset('images/malang-2.png'),
            ];
        @endphp
        @foreach($heroImages as $img)
        <div class="hero-slide" style="background-image: url('{{ $img }}');"></div>
        @endforeach
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Platform Sewa Motor <span>Terpercaya</span><br>di Kota Malang</h1>
        <p>
            Pilih dari 100+ motor terbaik &nbsp;&bull;&nbsp; Harga mulai Rp 50.000/hari &bull;<br>
            Booking mudah, cepat dan aman
        </p>
    </div>
    <div class="hero-indicators" id="heroIndicators">
        @foreach($heroImages as $i => $img)
        <div class="hero-bar {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></div>
        @endforeach
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
                    <div class="field-select date-field">
                        <i class="fa fa-calendar-days field-icon"></i>
                        <div class="date-input-wrap">
                            <span class="date-mask">dd/mm/yyyy</span>
                            <input type="text" name="start_date" id="startDate" autocomplete="off" maxlength="10">
                        </div>
                        <button type="button" class="date-picker-btn" data-target="startDate"><i class="fa fa-calendar-days"></i></button>
                    </div>
                    <div class="field-select date-field">
                        <i class="fa fa-calendar-days field-icon"></i>
                        <div class="date-input-wrap">
                            <span class="date-mask">dd/mm/yyyy</span>
                            <input type="text" name="end_date" id="endDate" autocomplete="off" maxlength="10">
                        </div>
                        <button type="button" class="date-picker-btn" data-target="endDate"><i class="fa fa-calendar-days"></i></button>
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
        <a href="{{ route('search.category', ['categorySlug' => str_replace('_', '-', $cat['slug'])]) }}" class="kategori-card">
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
                <div class="motor-actions-row">
                    @auth
                        @if(auth()->user()->role === 'user')
                            <form action="{{ route('user.wishlist.vehicles.toggle', $vehicle) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-fav {{ in_array($vehicle->id, $wishlistedVehicleIds ?? [], true) ? 'is-active' : '' }}">
                                    <i class="fa fa-heart"></i>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-fav"><i class="fa fa-heart"></i></a>
                    @endauth

                    <a href="{{ route('search', ['keyword' => $vehicle->name]) }}" class="btn-lihat motor-lihat-btn">Lihat Pilihan</a>
                </div>
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
                @auth
                    @if(auth()->user()->role === 'user')
                        <form action="{{ route('user.wishlist.vendors.toggle', $vendor) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-fav {{ in_array($vendor->id, $wishlistedVendorIds ?? [], true) ? 'is-active' : '' }}"><i class="fa fa-heart"></i></button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-fav"><i class="fa fa-heart"></i></a>
                @endauth

                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('chat.index', ['vendor' => $vendor->id]) }}" class="btn-chat" data-chat-vendor-id="{{ $vendor->id }}">
                            <i class="fa-solid fa-comments"></i> Chat Vendor
                        </a>
                    @else
                        <a href="{{ route('chat.index') }}" class="btn-chat">
                            <i class="fa-solid fa-comments"></i> Chat Vendor
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-chat">
                        <i class="fa-solid fa-comments"></i> Chat Vendor
                    </a>
                @endauth

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
                <a class="btn-chat" href="{{ route('login') }}"><i class="fa-solid fa-comments"></i> Chat Vendor</a>
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
        <a href="{{ route('articles.index') }}" class="see-all">Lihat Semua <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="artikel-grid article-list-grid">
        @forelse($articles as $article)
            <a href="{{ route('articles.show', $article) }}" class="artikel-card artikel-card-link">
                <div class="artikel-img">
                    <img src="{{ $article->cover_image ? Storage::url($article->cover_image) : asset('images/malang-1.png') }}" alt="{{ $article->title }}">
                </div>
                <div class="artikel-body">
                    <div class="artikel-head-meta">
                        <div class="artikel-date">{{ optional($article->published_at)->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="artikel-title">{{ $article->title }}</div>
                    <div class="artikel-excerpt">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 130) }}</div>
                    <div class="artikel-read-wrap">
                        <span class="artikel-read">Lihat Selengkapnya <i class="fa fa-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        @empty
            <div class="artikel-empty-state">
                <p class="artikel-empty-title">Belum ada artikel yang dipublikasikan.</p>
                <span class="artikel-empty-subtitle">Silakan cek kembali dalam beberapa saat.</span>
            </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Hero Carousel — infinite right-scroll
    const heroTrack = document.getElementById('heroTrack');
    const originalSlides = Array.from(heroTrack.querySelectorAll('.hero-slide'));
    const heroBars = document.querySelectorAll('.hero-bar');
    const totalOriginal = originalSlides.length;
    let currentIndex = 0;        // logical index (0-based in original set)
    let trackPosition = 0;       // physical position in the extended track
    let slideInterval;
    let isTransitioning = false;

    // Clone all slides and append to create: [orig1, orig2, ...] + [clone1, clone2, ...]
    originalSlides.forEach(slide => {
        const clone = slide.cloneNode(true);
        heroTrack.appendChild(clone);
    });

    const allSlides = heroTrack.querySelectorAll('.hero-slide');

    function updateBars() {
        heroBars.forEach((bar, i) => {
            bar.classList.toggle('active', i === currentIndex);
        });
    }

    function slideToPosition(pos) {
        isTransitioning = true;
        trackPosition = pos;
        heroTrack.style.transform = `translateX(-${trackPosition * 100}vw)`;
    }

    function nextSlide() {
        if (isTransitioning) return;
        trackPosition++;
        currentIndex = trackPosition % totalOriginal;
        updateBars();
        slideToPosition(trackPosition);
    }

    // When transition ends, if we're in the cloned zone, reset instantly
    heroTrack.addEventListener('transitionend', () => {
        isTransitioning = false;
        if (trackPosition >= totalOriginal) {
            heroTrack.classList.add('no-transition');
            trackPosition = trackPosition - totalOriginal;
            heroTrack.style.transform = `translateX(-${trackPosition * 100}vw)`;
            // Force reflow then remove no-transition
            heroTrack.offsetHeight;
            heroTrack.classList.remove('no-transition');
        }
    });

    // Click on indicator bars — go to that slide (always move right)
    heroBars.forEach(bar => {
        bar.addEventListener('click', () => {
            if (isTransitioning) return;
            const targetIndex = parseInt(bar.dataset.index);
            // Calculate how many steps forward to reach target
            let stepsForward = (targetIndex - currentIndex + totalOriginal) % totalOriginal;
            if (stepsForward === 0) return;
            trackPosition += stepsForward;
            currentIndex = targetIndex;
            updateBars();
            slideToPosition(trackPosition);
            resetAutoSlide();
        });
    });

    function resetAutoSlide() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }

    // Start auto-slide
    slideInterval = setInterval(nextSlide, 5000);

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

    // Flatpickr — modern date pickers
    const fpConfig = {
        locale: 'id',
        dateFormat: 'd/m/Y',
        disableMobile: true,
        allowInput: true,
        clickOpens: false,
        minDate: 'today',
        prevArrow: '<i class="fa fa-chevron-left"></i>',
        nextArrow: '<i class="fa fa-chevron-right"></i>',
    };

    // Helper to update date mask for an input
    function refreshDateMask(input) {
        const mask = input.parentElement.querySelector('.date-mask');
        const template = 'dd/mm/yyyy';
        const val = input.value;
        if (val.length === 0) {
            mask.innerHTML = '<span class="mask-placeholder">' + template + '</span>';
        } else if (val.length < 10) {
            mask.innerHTML = '<span class="mask-typed">' + val + '</span><span class="mask-placeholder">' + template.substring(val.length) + '</span>';
        } else {
            mask.innerHTML = '<span class="mask-typed">' + val + '</span>';
        }
    }

    const startPicker = flatpickr('#startDate', {
        ...fpConfig,
        onChange: function(selectedDates) {
            refreshDateMask(document.getElementById('startDate'));
            if (selectedDates.length > 0) {
                endPicker.set('minDate', selectedDates[0]);
                endPicker.open();
            }
        }
    });

    const endPicker = flatpickr('#endDate', {
        ...fpConfig,
        onChange: function() {
            refreshDateMask(document.getElementById('endDate'));
        }
    });

    // Calendar icon buttons open picker
    document.querySelectorAll('.date-picker-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const targetId = btn.dataset.target;
            if (targetId === 'startDate') startPicker.open();
            if (targetId === 'endDate') endPicker.open();
        });
    });

    // Block non-numeric keys, allow only digits and navigation keys
    document.querySelectorAll('#startDate, #endDate').forEach(input => {

        // Initial render
        refreshDateMask(input);

        input.addEventListener('keydown', (e) => {
            // Allow: backspace, delete, tab, escape, enter, arrows
            if ([8, 9, 13, 27, 37, 38, 39, 40, 46].includes(e.keyCode)) return;
            // Allow Ctrl+A, Ctrl+C, Ctrl+V
            if ((e.ctrlKey || e.metaKey) && [65, 67, 86].includes(e.keyCode)) return;
            // Block non-digit keys
            if ((e.key < '0' || e.key > '9')) {
                e.preventDefault();
            }
        });

        // Auto-format dd/mm/yyyy as user types
        input.addEventListener('input', (e) => {
            let val = e.target.value.replace(/[^0-9]/g, '');
            if (val.length > 8) val = val.substring(0, 8);
            if (val.length >= 5) {
                val = val.substring(0, 2) + '/' + val.substring(2, 4) + '/' + val.substring(4);
            } else if (val.length >= 3) {
                val = val.substring(0, 2) + '/' + val.substring(2);
            }
            e.target.value = val;
            refreshDateMask(input);

            // When full date is typed, set it in Flatpickr
            if (val.length === 10) {
                const picker = input.id === 'startDate' ? startPicker : endPicker;
                picker.setDate(val, true, 'd/m/Y');
            }
        });

        // Update mask on focus/blur and when flatpickr sets value
        input.addEventListener('focus', () => refreshDateMask(input));
        input.addEventListener('blur', () => refreshDateMask(input));

        // Watch for flatpickr programmatic value changes
        const observer = new MutationObserver(() => refreshDateMask(input));
        observer.observe(input, { attributes: true, attributeFilter: ['value'] });
    });
</script>
@endpush
