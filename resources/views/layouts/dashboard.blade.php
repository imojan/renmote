<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Renmote') }} - @yield('title', __('dashboard.topbar.title'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/renmote-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-rn-bg text-rn-text" x-data="{ mobileNav: false, userMenu: false }">

@php
    $role = auth()->user()->role;
    $sidebarPartial = match ($role) {
        'admin'  => 'layouts.sidebars.admin',
        'vendor' => 'layouts.sidebars.vendor',
        default  => 'layouts.sidebars.user',
    };
    $dashboardUnreadNotifications = auth()->user()->unreadNotifications()->count();

    // Display avatar: vendor uses business profile photo when set, others fall
    // back to user.profile_photo_path, then to letter avatar.
    $vendorRecord = $role === 'vendor' ? auth()->user()->vendor : null;
    $avatarUrl = null;
    if ($vendorRecord && $vendorRecord->profile_photo) {
        $avatarUrl = \Illuminate\Support\Facades\Storage::url($vendorRecord->profile_photo);
    } elseif (auth()->user()->profile_photo_path ?? null) {
        $avatarUrl = \Illuminate\Support\Facades\Storage::url(auth()->user()->profile_photo_path);
    }

    /**
     * Centralised top-nav definition. Each entry: label, route, active match.
     * Different role gets different list.
     */
    $navItemsByRole = [
        'admin' => [
            ['label' => __('dashboard.sidebar.home'),      'route' => 'admin.dashboard',       'icon' => 'fa-house',         'active' => 'admin.dashboard'],
            ['label' => __('dashboard.sidebar.vendors'),   'route' => 'admin.vendors.index',   'icon' => 'fa-store',         'active' => 'admin.vendors.*'],
            ['label' => __('dashboard.sidebar.users'),     'route' => 'admin.users.index',     'icon' => 'fa-id-card',       'active' => 'admin.users.*'],
            ['label' => __('dashboard.sidebar.vehicles'),  'route' => 'admin.vehicles.index',  'icon' => 'fa-motorcycle',    'active' => 'admin.vehicles.*'],
            ['label' => __('dashboard.sidebar.bookings_admin'), 'route' => 'admin.bookings.index', 'icon' => 'fa-clipboard-list', 'active' => 'admin.bookings.*'],
            ['label' => __('dashboard.sidebar.articles'),  'route' => 'admin.articles.index',  'icon' => 'fa-newspaper',     'active' => 'admin.articles.*'],
            ['label' => __('dashboard.sidebar.documents'), 'route' => 'admin.documents.index', 'icon' => 'fa-folder-open',   'active' => 'admin.documents.*'],
            ['label' => __('dashboard.sidebar.settings'),  'route' => 'admin.settings.index',  'icon' => 'fa-gear',          'active' => 'admin.settings.*'],
        ],
        'vendor' => [
            ['label' => __('dashboard.sidebar.home'),     'route' => 'vendor.dashboard',         'icon' => 'fa-house',         'active' => 'vendor.dashboard'],
            ['label' => __('dashboard.sidebar.vehicles'), 'route' => 'vendor.vehicles.index',    'icon' => 'fa-motorcycle',    'active' => 'vendor.vehicles.*'],
            ['label' => __('dashboard.sidebar.bookings'), 'route' => 'vendor.bookings.index',    'icon' => 'fa-clipboard-list', 'active' => 'vendor.bookings.*'],
            ['label' => __('dashboard.sidebar.profile'),  'route' => 'vendor.profile.edit',      'icon' => 'fa-user',          'active' => 'vendor.profile.*'],
        ],
        'user' => [
            ['label' => __('dashboard.sidebar.home'),      'route' => 'user.dashboard',          'icon' => 'fa-house',         'active' => 'user.dashboard'],
            ['label' => __('dashboard.sidebar.bookings'),  'route' => 'user.bookings.index',     'icon' => 'fa-clipboard-list', 'active' => 'user.bookings.*'],
            ['label' => __('dashboard.sidebar.addresses'), 'route' => 'user.addresses.index',    'icon' => 'fa-map-pin',       'active' => 'user.addresses.*'],
            ['label' => __('dashboard.sidebar.profile'),   'route' => 'profile.edit',            'icon' => 'fa-user',          'active' => 'profile.*'],
        ],
    ];

    $navItems = $navItemsByRole[$role] ?? $navItemsByRole['user'];
@endphp

{{-- ── Top Navigation Bar ──────────────────────────────────────── --}}
<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur-md">
    <div class="mx-auto flex h-16 max-w-[1400px] items-center gap-4 px-4 sm:px-6 lg:px-10">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2">
            <img src="{{ asset('images/renmote-biru.png') }}" alt="{{ config('app.name') }}" class="h-9 w-auto">
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden flex-1 items-center justify-center gap-1 lg:flex">
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['active']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition xl:px-4
                          {{ $isActive
                              ? 'bg-rn-primary/10 text-rn-primary'
                              : 'text-slate-500 hover:bg-slate-100 hover:text-rn-text' }}">
                    <i class="fa-solid {{ $item['icon'] }} text-[13px]"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Right cluster --}}
        <div class="ml-auto flex shrink-0 items-center gap-2 sm:gap-3">

            <x-dashboard.locale-switch class="hidden sm:inline-flex" />

            <x-notification-dropdown :unreadCount="$dashboardUnreadNotifications" />

            {{-- User dropdown --}}
            <div class="relative" @click.outside="userMenu = false">
                <button type="button"
                        @click="userMenu = !userMenu"
                        class="flex items-center gap-2 rounded-full border border-slate-200 bg-white py-1 pl-1 pr-3 transition hover:bg-slate-50">
                    <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-rn-primary text-sm font-bold text-white">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </span>
                    <span class="hidden text-left leading-tight sm:block">
                        <span class="block max-w-[120px] truncate text-xs font-semibold text-rn-text">{{ auth()->user()->name }}</span>
                        <span class="block text-[10px] uppercase tracking-wide text-slate-500">{{ ucfirst($role) }}</span>
                    </span>
                    <i class="fa fa-chevron-down hidden text-[10px] text-slate-400 sm:block"></i>
                </button>

                <div x-show="userMenu" x-cloak x-transition.opacity
                     class="absolute right-0 mt-2 w-60 rounded-xl border border-slate-200 bg-white p-1 shadow-lg">
                    <div class="px-3 py-2">
                        <p class="text-sm font-semibold text-rn-text">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="mx-1 h-px bg-slate-100"></div>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-rn-text">
                        <i class="fa fa-user text-xs text-slate-400"></i>
                        {{ __('dashboard.topbar.profile') }}
                    </a>
                    <div class="mx-1 h-px bg-slate-100"></div>
                    <form method="POST" action="{{ route('logout') }}"
                          data-confirm-title="{{ __('nav.logout_confirm_title') }}"
                          data-confirm-message="{{ __('nav.logout_confirm_message') }}"
                          data-confirm-confirm-text="{{ __('nav.logout_confirm_yes') }}"
                          data-confirm-cancel-text="{{ __('nav.logout_confirm_no') }}">
                        @csrf
                        <button type="submit"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-red-600 transition hover:bg-red-50">
                            <i class="fa fa-right-from-bracket text-xs"></i>
                            {{ __('dashboard.topbar.logout') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Hamburger (mobile/tablet) --}}
            <button type="button" @click="mobileNav = true"
                    class="flex h-10 w-10 items-center justify-center rounded-full text-slate-600 transition hover:bg-slate-100 lg:hidden"
                    aria-label="Open navigation">
                <i class="fa fa-bars"></i>
            </button>
        </div>
    </div>
</header>

{{-- ── Mobile drawer ────────────────────────────────────────── --}}
<div x-cloak x-show="mobileNav" class="lg:hidden">
    <div x-show="mobileNav" x-transition.opacity @click="mobileNav = false"
         class="fixed inset-0 z-40 bg-black/40"></div>

    <aside x-show="mobileNav"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col overflow-y-auto bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/renmote-biru.png') }}" alt="Renmote" class="h-8 w-auto">
            </a>
            <button type="button" @click="mobileNav = false"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-slate-600 hover:bg-slate-100">
                <i class="fa fa-xmark"></i>
            </button>
        </div>

        <nav class="flex-1 px-3 py-4">
            <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                {{ __('dashboard.sidebar.menu_label') }}
            </p>
            <div class="space-y-1">
                @foreach ($navItems as $item)
                    @php $isActive = request()->routeIs($item['active']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition
                              {{ $isActive
                                  ? 'bg-rn-primary/10 text-rn-primary'
                                  : 'text-slate-600 hover:bg-slate-50 hover:text-rn-text' }}">
                        <span class="flex h-6 w-6 items-center justify-center"><i class="fa-solid {{ $item['icon'] }} text-[14px]"></i></span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>

        <div class="border-t border-slate-200 p-4">
            <x-dashboard.locale-switch class="w-full justify-center" />
            <div class="mt-4 flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-rn-primary text-sm font-bold text-white">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </span>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-rn-text">{{ auth()->user()->name }}</p>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ ucfirst($role) }}</p>
                </div>
            </div>
        </div>
    </aside>
</div>

{{-- ── Main content ─────────────────────────────────────────── --}}
<main class="mx-auto max-w-[1400px] px-4 py-6 sm:px-6 lg:px-10">
    <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-rn-text sm:text-3xl">@yield('title', __('dashboard.topbar.title'))</h1>
            <p class="mt-1 text-sm text-slate-500">{{ now()->locale(app()->getLocale())->translatedFormat('l, d F Y') }}</p>
        </div>
        @hasSection('headerActions')
            <div class="flex flex-wrap items-center gap-2">@yield('headerActions')</div>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3.5 text-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            </div>
            <span class="pt-1 font-medium text-emerald-700/80">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50/80 px-4 py-3.5 text-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-500 text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </div>
            <span class="pt-1 font-medium text-red-700/80">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-4 flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50/80 px-4 py-3.5 text-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-500 text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
            </div>
            <span class="pt-1 font-medium text-blue-700/80">{{ session('info') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-4 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50/80 px-4 py-3.5 text-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-400 text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            </div>
            <span class="pt-1 font-medium text-amber-700/80">{{ session('warning') }}</span>
        </div>
    @endif

    @yield('content')
</main>

{{-- Floating chat for vendor & user dashboards --}}
@if(in_array($role, ['user', 'vendor'], true))
    @include('chat.panel', ['mode' => 'floating'])
@endif

{{-- Notification Modal --}}
<x-notification-modal />

@stack('scripts')
</body>
</html>
