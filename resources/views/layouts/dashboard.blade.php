<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Renmote') }} - @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/renmote-icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="dash-wrapper" id="dashWrapper">
        <!-- ── Mobile Overlay ─────────────────────────────── -->
        <div class="dash-overlay" id="dashOverlay" onclick="toggleSidebar()"></div>

        <!-- ── Sidebar ────────────────────────────────────── -->
        <aside class="dash-sidebar" id="dashSidebar">
            <!-- Brand -->
            <div class="dash-sidebar-brand">
                <a href="{{ route('home') }}" class="dash-brand-link">
                    <img src="{{ asset('images/renmote-logo.png') }}" alt="Renmote" class="dash-brand-logo">
                </a>
                <button class="dash-sidebar-close" onclick="toggleSidebar()" aria-label="Close sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Label -->
            <div class="dash-nav-label">MENU</div>

            <!-- Navigation -->
            <nav class="dash-nav">
                @if(auth()->user()->role === 'admin')
                    @include('layouts.sidebars.admin')
                @elseif(auth()->user()->role === 'vendor')
                    @include('layouts.sidebars.vendor')
                @else
                    @include('layouts.sidebars.user')
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="dash-sidebar-footer">
                <div class="dash-sidebar-user">
                    <div class="dash-sidebar-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="dash-sidebar-user-info">
                        <div class="dash-sidebar-user-name">{{ auth()->user()->name }}</div>
                        <div class="dash-sidebar-user-role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ── Main ───────────────────────────────────────── -->
        <div class="dash-main">
            <!-- Topbar -->
            <header class="dash-topbar">
                <div class="dash-topbar-left">
                    <button class="dash-menu-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="dash-page-title">@yield('title', 'Dashboard')</h1>
                </div>

                <div class="dash-topbar-right">
                    @php
                        $dashboardUnreadNotifications = auth()->user()->unreadNotifications()->count();
                    @endphp

                    <a href="{{ route('notifications.index') }}" class="dash-topbar-notification" aria-label="Notifikasi">
                        <i class="fa fa-bell"></i>
                        @if($dashboardUnreadNotifications > 0)
                            <span class="dash-topbar-notification-badge">{{ $dashboardUnreadNotifications > 99 ? '99+' : $dashboardUnreadNotifications }}</span>
                        @endif
                    </a>

                    <!-- Date -->
                    <span class="dash-topbar-date">{{ now()->translatedFormat('l, d M Y') }}</span>

                    <!-- User Dropdown -->
                    <div class="dash-user-dropdown" id="userDropdown">
                        <button class="dash-user-btn" onclick="toggleUserMenu()">
                            <div class="dash-topbar-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="dash-dropdown-menu" id="userMenu">
                            <div class="dash-dropdown-header">
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ auth()->user()->email }}</small>
                            </div>
                            <div class="dash-dropdown-divider"></div>
                            <a href="{{ route('profile.edit') }}" class="dash-dropdown-item">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil
                            </a>
                            <div class="dash-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}"
                                data-confirm-title="Logout dari dashboard?"
                                data-confirm-message="Kamu yakin ingin keluar dari akun sekarang?"
                                data-confirm-confirm-text="Ya, Logout"
                                data-confirm-cancel-text="Batal">
                                @csrf
                                <button type="submit" class="dash-dropdown-item dash-dropdown-logout">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="dash-content">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="dash-alert dash-alert-success">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="dash-alert dash-alert-error">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('dashWrapper').classList.toggle('sidebar-open');
        }
        function toggleUserMenu() {
            document.getElementById('userMenu').classList.toggle('show');
        }
        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('userDropdown');
            if (dd && !dd.contains(e.target)) {
                document.getElementById('userMenu').classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
