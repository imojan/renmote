<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Renmote') }} - @yield('title', __('Platform Sewa Motor Terpercaya di Kota Malang'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/renmote-icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    @stack('styles')
</head>
<body>

{{-- ===== TOP BAR (desktop only) ===== --}}
<div class="topbar">
    <div class="topbar-inner">
        <div class="topbar-left">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active-link' : '' }}">{{ __('nav.home') }}</a>
            <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active-link' : '' }}">{{ __('nav.articles') }}</a>
            <a href="{{ route('rent.guide') }}" class="{{ request()->routeIs('rent.guide') ? 'active-link' : '' }}">{{ __('nav.rent_guide') }}</a>
            <a href="{{ route('rent.terms') }}" class="{{ request()->routeIs('rent.terms') ? 'active-link' : '' }}">{{ __('nav.rent_terms') }}</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active-link' : '' }}">{{ __('nav.about') }}</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active-link' : '' }}">{{ __('nav.contact') }}</a>
            <a href="{{ route('help') }}" class="{{ request()->routeIs('help') ? 'active-link' : '' }}">{{ __('nav.help') }}</a>
        </div>
        <div class="topbar-right">
            <x-front.locale-switch />
            <a href="{{ route('register') }}"><i class="fa fa-users"></i> {{ __('nav.become_vendor') }}</a>
            @auth
                @if(auth()->user()->role === 'user')
                    <div class="topbar-account" id="topbarAccount">
                        <button class="topbar-account-toggle" id="topbarAccountToggle" type="button">
                            @if(auth()->user()->profile_photo_path)
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->profile_photo_path) }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="topbar-account-avatar"
                                >
                            @else
                                <i class="fa fa-user-circle topbar-account-icon"></i>
                            @endif
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fa fa-chevron-down topbar-account-chevron"></i>
                        </button>

                        <div class="topbar-account-menu" id="topbarAccountMenu">
                            <a href="{{ route('user.account.index') }}" class="topbar-account-item">{{ __('nav.my_account') }}</a>
                            <a href="{{ route('user.bookings.index') }}" class="topbar-account-item">{{ __('nav.order_history') }}</a>
                            <a href="{{ route('notifications.index') }}" class="topbar-account-item">{{ __('nav.notifications') }}</a>
                            <form method="POST" action="{{ route('logout') }}"
                                data-confirm-title="{{ __('nav.logout_confirm_title') }}"
                                data-confirm-message="{{ __('nav.logout_confirm_message') }}"
                                data-confirm-confirm-text="{{ __('nav.logout_confirm_yes') }}"
                                data-confirm-cancel-text="{{ __('nav.logout_confirm_no') }}">
                                @csrf
                                <button type="submit" class="topbar-account-item topbar-account-item-danger">{{ __('nav.logout') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    @php
                        $dashboardRoute = auth()->user()->role === 'admin'
                            ? route('admin.dashboard')
                            : route('vendor.dashboard');
                        $dashboardLabel = auth()->user()->role === 'admin'
                            ? __('nav.admin_dashboard')
                            : __('nav.vendor_dashboard');
                    @endphp
                    <div class="topbar-account" id="topbarAccount">
                        <button class="topbar-account-toggle" id="topbarAccountToggle" type="button">
                            <i class="fa fa-user-circle topbar-account-icon"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fa fa-chevron-down topbar-account-chevron"></i>
                        </button>

                        <div class="topbar-account-menu" id="topbarAccountMenu">
                            <a href="{{ $dashboardRoute }}" class="topbar-account-item">{{ $dashboardLabel }}</a>
                            <a href="{{ route('notifications.index') }}" class="topbar-account-item">{{ __('nav.notifications') }}</a>
                            <form method="POST" action="{{ route('logout') }}"
                                data-confirm-title="{{ __('nav.logout_confirm_title') }}"
                                data-confirm-message="{{ __('nav.logout_confirm_message') }}"
                                data-confirm-confirm-text="{{ __('nav.logout_confirm_yes') }}"
                                data-confirm-cancel-text="{{ __('nav.logout_confirm_no') }}">
                                @csrf
                                <button type="submit" class="topbar-account-item topbar-account-item-danger">{{ __('nav.logout') }}</button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}"><i class="fa fa-user-circle"></i> {{ __('nav.sign_in_register') }}</a>
            @endauth
        </div>
    </div>
</div>

{{-- ===== NAVBAR ===== --}}
<nav class="main-nav">
    <div class="nav-inner">
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

        @hasSection('hideSearchBar')
            <div class="search-bar-spacer"></div>
        @else
            <form action="{{ route('search') }}" method="GET" class="search-bar">
                <input type="text" name="keyword" placeholder="{{ __('nav.search_placeholder') }}" value="{{ request('keyword') }}">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        @endif

        @php
            $wishlistUrl = '#';

            if (auth()->check()) {
                if (auth()->user()->role === 'user') {
                    $wishlistUrl = route('user.wishlist.index');
                }
            } else {
                $wishlistUrl = route('login');
            }
        @endphp

        @php
            $notificationUrl = auth()->check() ? route('notifications.index') : route('login');
            $notificationUnreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
        @endphp
        <div class="nav-icons">
            <a href="{{ $wishlistUrl }}" class="nav-icon" aria-label="{{ __('nav.wishlist') }}"><i class="fa fa-heart"></i></a>
            <a href="{{ $notificationUrl }}" class="nav-icon nav-icon-bell" aria-label="{{ __('nav.notifications') }}">
                <i class="fa fa-bell"></i>
                @if($notificationUnreadCount > 0)
                    <span class="nav-icon-badge">{{ $notificationUnreadCount > 99 ? '99+' : $notificationUnreadCount }}</span>
                @endif
            </a>
        </div>
    </div>
</nav>

{{-- ===== MOBILE MENU ===== --}}
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
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fa fa-home"></i> {{ __('nav.home') }}</a>
        <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}"><i class="fa fa-newspaper"></i> {{ __('nav.articles') }}</a>
        <a href="{{ route('rent.guide') }}" class="{{ request()->routeIs('rent.guide') ? 'active' : '' }}"><i class="fa fa-book-open"></i> {{ __('nav.rent_guide') }}</a>
        <a href="{{ route('rent.terms') }}" class="{{ request()->routeIs('rent.terms') ? 'active' : '' }}"><i class="fa fa-file-contract"></i> {{ __('nav.rent_terms') }}</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}"><i class="fa fa-info-circle"></i> {{ __('nav.about') }}</a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i class="fa fa-phone"></i> {{ __('nav.contact') }}</a>
        <a href="{{ route('help') }}" class="{{ request()->routeIs('help') ? 'active' : '' }}"><i class="fa fa-question-circle"></i> {{ __('nav.help') }}</a>
        <hr>
        <a href="{{ route('register') }}"><i class="fa fa-users"></i> {{ __('nav.become_vendor') }}</a>
        @auth
            @if(auth()->user()->role === 'user')
                <a href="{{ route('user.account.index') }}">
                    @if(auth()->user()->profile_photo_path)
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->profile_photo_path) }}"
                            alt="{{ auth()->user()->name }}"
                            class="mobile-user-avatar"
                        >
                    @else
                        <i class="fa fa-user-circle"></i>
                    @endif
                    {{ auth()->user()->name }}
                </a>
                <a href="{{ route('notifications.index') }}"><i class="fa fa-bell"></i> {{ __('nav.notifications') }}</a>
            @else
                <a href="@if(auth()->user()->role === 'admin'){{ route('admin.dashboard') }}@else{{ route('vendor.dashboard') }}@endif">
                    <i class="fa fa-user-circle"></i> {{ auth()->user()->name }}
                </a>
                <a href="{{ route('notifications.index') }}"><i class="fa fa-bell"></i> {{ __('nav.notifications') }}</a>
            @endif
        @else
            <a href="{{ route('login') }}"><i class="fa fa-user-circle"></i> {{ __('nav.sign_in_register') }}</a>
        @endauth
    </div>
    <div class="mobile-menu-lang">
        <i class="fa fa-globe"></i>
        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
        <span style="opacity:.4">|</span>
        <a href="{{ route('locale.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
@yield('content')

@auth
    @if(in_array(auth()->user()->role, ['user', 'vendor'], true))
        @include('chat.panel', ['mode' => 'floating'])
    @endif
@endauth

<x-front.footer />

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

    const topbarAccount = document.getElementById('topbarAccount');
    const topbarAccountToggle = document.getElementById('topbarAccountToggle');

    if (topbarAccount && topbarAccountToggle) {
        topbarAccountToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            topbarAccount.classList.toggle('open');
        });

        document.addEventListener('click', (event) => {
            if (!topbarAccount.contains(event.target)) {
                topbarAccount.classList.remove('open');
            }
        });
    }
</script>
</body>
</html>
