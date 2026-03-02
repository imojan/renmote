<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Renmote') }} - @yield('title', 'Platform Sewa Motor Terpercaya di Kota Malang')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #1565C0;
            --primary-dark: #0D47A1;
            --accent: #FF6B00;
            --green: #22C55E;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
            background: #fff;
        }

        /* ===== TOP BAR ===== */
        .topbar {
            background: #0058BC;
            padding: 10px 0;
            font-size: 12px;
            color: #fff;
        }
        .topbar-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar-left {
            display: flex;
            gap: 0;
            align-items: center;
        }
        .topbar-left a {
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 14px;
            letter-spacing: .3px;
            text-transform: uppercase;
            transition: all .2s ease;
            position: relative;
        }
        .topbar-left a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 14px;
            right: 14px;
            height: 2px;
            background: #fff;
            transform: scaleX(0);
            transition: transform .25s ease;
        }
        .topbar-left a:hover {
            font-weight: 600;
        }
        .topbar-left a:hover::after {
            transform: scaleX(1);
        }
        .topbar-left a.active-link {
            font-weight: 700;
        }
        .topbar-left a.active-link::after {
            transform: scaleX(1);
        }
        .topbar-right { display: flex; gap: 20px; align-items: center; }
        .topbar-right a,
        .topbar-right span { color: #fff; text-decoration: none; font-size: 12px; font-weight: 600; }
        .topbar-right a:hover { opacity: .8; }
        .lang-switch { display: flex; gap: 4px; align-items: center; }
        .lang-switch i { margin-right: 4px; }
        .lang-switch span { cursor: pointer; font-weight: 700; color: rgba(255,255,255,.6); }
        .lang-switch span.active { color: #fff; }

        /* ===== NAVBAR ===== */
        .main-nav {
            background: #0058BC;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 16px 40px;
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .logo-box {
            color: #fff;
            font-weight: 800;
            font-size: 22px;
            letter-spacing: 2px;
            font-style: italic;
        }
        .logo-sub { font-size: 8px; color: rgba(255,255,255,.7); font-weight: 600; letter-spacing: 2px; text-transform: uppercase; }
        .logo-img { height: 48px; width: auto; filter: brightness(0) invert(1); }

        .search-bar {
            flex: 1;
            display: flex;
            align-items: center;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            max-width: 900px;
            border: 2px solid #fff;
        }
        .search-bar input {
            flex: 1;
            border: none;
            outline: none;
            padding: 14px 22px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--text);
            letter-spacing: .3px;
            caret-color: #0058BC;
        }
        .search-bar input:focus {
            text-transform: none;
        }
        .search-bar input::placeholder {
            color: #999;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: .5px;
        }
        .search-bar button {
            background: #0058BC;
            border: none;
            border-left: 1.5px solid #e0e0e0;
            padding: 14px 20px;
            color: #ffffff;
            cursor: pointer;
            font-size: 18px;
            transition: background .2s;
            flex-shrink: 0;
        }
        .search-bar button:hover { background: #f0f4ff; }

        .nav-icons {
            display: flex;
            gap: 18px;
            align-items: center;
            margin-left: auto;
        }
        .nav-icon {
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 0;
            border: none;
            color: rgba(255,255,255,.7);
            cursor: pointer;
            font-size: 22px;
            transition: all .2s;
            text-decoration: none;
            background: transparent;
        }
        .nav-icon:hover { color: #fff; }

        /* ===== CTA BANNER ===== */
        .cta-banner {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            border-radius: 20px;
            padding: 32px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            max-width: 1160px;
            margin: 20px auto 40px;
        }
        .cta-left { display: flex; align-items: center; gap: 16px; }
        .cta-icon {
            width: 56px; height: 56px;
            background: rgba(255,255,255,.15);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            color: #fff;
            flex-shrink: 0;
        }
        .cta-text h3 { font-size: 18px; font-weight: 800; color: #fff; }
        .cta-text p { font-size: 13px; color: rgba(255,255,255,.8); margin-top: 4px; }
        .btn-hubungi {
            background: var(--green);
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background .2s;
            white-space: nowrap;
            text-decoration: none;
        }
        .btn-hubungi:hover { background: #16a34a; }

        /* ===== FOOTER ===== */
        .site-footer {
            background: #1a1a2e;
            color: #fff;
            padding: 48px 20px 24px;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-brand p { font-size: 12px; color: rgba(255,255,255,.5); line-height: 1.7; margin-top: 10px; }
        .footer-col h4 {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 16px;
            color: rgba(255,255,255,.9);
        }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 8px; }
        .footer-col ul li a {
            font-size: 12px;
            color: rgba(255,255,255,.5);
            text-decoration: none;
            transition: color .2s;
        }
        .footer-col ul li a:hover { color: #fff; }
        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: rgba(255,255,255,.5);
            margin-bottom: 8px;
        }
        .footer-contact-item i { color: var(--accent); width: 16px; }
        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,.1);
            margin-bottom: 20px;
        }
        .footer-bottom {
            display: flex;
            justify-content: center;
            font-size: 12px;
            color: rgba(255,255,255,.4);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .footer-grid { grid-template-columns: 1fr 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .topbar-left { flex-wrap: wrap; gap: 4px; }
            .topbar-left a { padding: 4px 8px; font-size: 11px; }
            .search-bar { max-width: 100%; }
            .nav-inner { padding: 12px 16px; gap: 12px; flex-wrap: wrap; }
            .nav-icons { gap: 10px; }
            .nav-icon { font-size: 18px; width: 32px; height: 32px; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .cta-banner { flex-direction: column; text-align: center; padding: 24px 20px; }
            .cta-left { flex-direction: column; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ===== TOP BAR ===== --}}
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
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/renmote-logo.png') }}" alt="Renmote" class="logo-img"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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

{{-- ===== MAIN CONTENT ===== --}}
@yield('content')

{{-- ===== CTA BANNER ===== --}}
<div style="padding: 0 20px 40px;">
    <div class="cta-banner">
        <div class="cta-left">
            <div class="cta-icon"><i class="fab fa-whatsapp"></i></div>
            <div class="cta-text">
                <h3>Hubungi Admin!</h3>
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
                <img src="{{ asset('images/renmote-logo.png') }}" alt="Renmote" style="height:36px; margin-bottom:12px; filter: brightness(0) invert(1);"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                <div class="logo-box" style="display:none; margin-bottom:12px;">RENMOTE</div>
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
                    <li><a href="{{ route('login') }}">Sign Up / Sign In</a></li>
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
            <div class="footer-col">
                <h4>Kontak Kami</h4>
                <div class="footer-contact-item"><i class="fab fa-whatsapp"></i> 089631926343 - (24/7 Consultation)</div>
                <div class="footer-contact-item"><i class="fab fa-tiktok"></i> @renmote</div>
                <div class="footer-contact-item"><i class="fab fa-instagram"></i> @renmote.id</div>
                <div class="footer-contact-item"><i class="fa fa-envelope"></i> info@renmote.com</div>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Sewa Motor Malang Support By Renmote Motorcycle Rental. All rights reserved.</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
