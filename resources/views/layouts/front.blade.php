<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Renmote') }} - @yield('title', 'Platform Sewa Motor Terpercaya di Kota Malang')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/renmote-icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

{{-- ===== TOP BAR (desktop only) ===== --}}
<div class="topbar">
    <div class="topbar-inner">
        <div class="topbar-left">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active-link' : '' }}">Beranda</a>
            <a href="#">Cara Sewa</a>
            <a href="#">S&K Sewa</a>
            <a href="#">Tentang Kami</a>
            <a href="#">Kontak Kami</a>
            <a href="#">Bantuan</a>
        </div>
        <div class="topbar-right">
            <div class="lang-switch">
                <i class="fa fa-globe" style="color:#fff;"></i>
                <span>EN</span>
                <span style="color:rgba(255,255,255,.5);">|</span>
                <span class="active">ID</span>
            </div>
            <a href="{{ route('register') }}"><i class="fa fa-users"></i> Jadi Vendor</a>
            @auth
                <a href="@if(auth()->user()->role === 'admin'){{ route('admin.dashboard') }}@elseif(auth()->user()->role === 'vendor'){{ route('vendor.dashboard') }}@else{{ route('user.dashboard') }}@endif">
                    <i class="fa fa-user-circle"></i> {{ auth()->user()->name }}
                </a>
            @else
                <a href="{{ route('login') }}"><i class="fa fa-user-circle"></i> Masuk/Daftar</a>
            @endauth
        </div>
    </div>
</div>

{{-- ===== NAVBAR ===== --}}
<nav class="main-nav">
    <div class="nav-inner">
        {{-- Hamburger button (mobile only) --}}
        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/renmote-logo.png') }}" alt="Renmote" class="logo-img logo-full"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <img src="{{ asset('images/renmote-logo-kecil.png') }}" alt="Renmote" class="logo-img logo-small">
            <div style="display:none; flex-direction:column; align-items:center;">
                <div class="logo-box">RENMOTE</div>
                <div class="logo-sub">Motorcycle Rental</div>
            </div>
        </a>

        <form action="{{ route('search') }}" method="GET" class="search-bar">
            <input type="text" name="keyword" placeholder="VARIO 125CC GEN 1 TAHUN 2018" value="{{ request('keyword') }}">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>

        <div class="nav-icons">
            <a href="#" class="nav-icon"><i class="fa fa-heart"></i></a>
            <a href="#" class="nav-icon"><i class="fa fa-shopping-cart"></i></a>
            <a href="#" class="nav-icon"><i class="fa fa-bell"></i></a>
        </div>
    </div>
</nav>

{{-- ===== MOBILE MENU OVERLAY ===== --}}
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/renmote-logo.png') }}" alt="Renmote" style="height:36px; filter:brightness(0) invert(1);"
                 onerror="this.style.display='none';">
        </a>
        <button class="mobile-menu-close" id="mobileMenuClose"><i class="fa fa-times"></i></button>
    </div>
    <div class="mobile-menu-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fa fa-home"></i> Beranda</a>
        <a href="#"><i class="fa fa-book-open"></i> Cara Sewa</a>
        <a href="#"><i class="fa fa-file-contract"></i> S&K Sewa</a>
        <a href="#"><i class="fa fa-info-circle"></i> Tentang Kami</a>
        <a href="#"><i class="fa fa-phone"></i> Kontak Kami</a>
        <a href="#"><i class="fa fa-question-circle"></i> Bantuan</a>
        <hr>
        <a href="{{ route('register') }}"><i class="fa fa-users"></i> Jadi Vendor</a>
        @auth
            <a href="@if(auth()->user()->role === 'admin'){{ route('admin.dashboard') }}@elseif(auth()->user()->role === 'vendor'){{ route('vendor.dashboard') }}@else{{ route('user.dashboard') }}@endif">
                <i class="fa fa-user-circle"></i> {{ auth()->user()->name }}
            </a>
        @else
            <a href="{{ route('login') }}"><i class="fa fa-user-circle"></i> Masuk / Daftar</a>
        @endauth
    </div>
    <div class="mobile-menu-lang">
        <i class="fa fa-globe"></i>
        <span>EN</span>
        <span style="opacity:.4">|</span>
        <span class="active">ID</span>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
@yield('content')

{{-- ===== CTA BANNER ===== --}}
<div class="cta-banner">
    <div class="cta-green-accent"></div>
    <div class="cta-inner">
        <div class="cta-left">
            <div class="cta-icon"><i class="fab fa-whatsapp"></i></div>
            <div class="cta-text">
                <h3>HUBUNGI ADMIN!</h3>
                <p>Jika Ada Masalah Lebih Lanjut.</p>
            </div>
        </div>
        <a href="https://wa.me/6289631926343" target="_blank" class="btn-hubungi">
            <i class="fab fa-whatsapp"></i> Hubungi Disini
        </a>
    </div>
</div>

{{-- ===== FOOTER ===== --}}
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="{{ asset('images/renmote-logo.png') }}" alt="Renmote" class="footer-logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                <div class="logo-box" style="display:none; margin-bottom:16px;">RENMOTE</div>
                <p>Untuk Info Promo, Diskon, Cashback, Cicilan, Kredit, DP Ringan, Dan Harga Motor Baru Terbaru Atau Penawaran Menarik Lainnya, Hubungi Kontak Yang Tersedia.</p>
            </div>
            <div class="footer-col">
                <h4>Our Menu</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="#">Cara Sewa</a></li>
                    <li><a href="#">S&K Sewa</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kontak Kami</a></li>
                    <li><a href="#">Bantuan</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Be Part Of Us</h4>
                <ul>
                    <li><a href="{{ route('register') }}">Jadi Vendor</a></li>
                    <li><a href="{{ route('login') }}">Sign Up/ Sign In</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('search') }}">Motor Populer</a></li>
                    <li><a href="{{ route('search') }}">Vendor Andalan</a></li>
                    <li><a href="#">Artikel & Rekomendasi</a></li>
                    <li><a href="#">Testimoni</a></li>
                </ul>
            </div>
            <div class="footer-col footer-col-contact">
                <h4>Kontak Kami</h4>
                <div class="footer-contact-item">
                    <span class="footer-contact-icon fc-wa"><i class="fab fa-whatsapp"></i></span>
                    089523132567 - (24/7 Consultation)
                </div>
                <div class="footer-contact-item">
                    <span class="footer-contact-icon fc-tiktok"><i class="fab fa-tiktok"></i></span>
                    rentalmotoronline
                </div>
                <div class="footer-contact-item">
                    <span class="footer-contact-icon fc-ig"><i class="fab fa-instagram"></i></span>
                    rentalmotoronline
                </div>
                <div class="footer-contact-item">
                    <span class="footer-contact-icon fc-mail"><i class="fa fa-envelope"></i></span>
                    renmotebusiness@gmail.com
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="footer-copyright">
    <p>&copy; {{ date('Y') }} Sewa Motor Malang Support By <strong>Renmote Motorcycle Rental.</strong> All rights reserved.</p>
</div>

@stack('scripts')
<script>
    // Mobile menu toggle
    const hamburger = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileMenuOverlay');
    const mobileClose = document.getElementById('mobileMenuClose');

    function openMenu() {
        mobileMenu.classList.add('open');
        mobileOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
        mobileMenu.classList.remove('open');
        mobileOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }
    hamburger.addEventListener('click', openMenu);
    mobileOverlay.addEventListener('click', closeMenu);
    mobileClose.addEventListener('click', closeMenu);
</script>
</body>
</html>
