@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('sidebar')
    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.vendors.index') }}" :active="request()->routeIs('admin.vendors.*')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Vendors
        @if($pendingVendors > 0)
            <span class="dash-nav-badge">{{ $pendingVendors }}</span>
        @endif
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.vehicles.index') }}" :active="request()->routeIs('admin.vehicles.*')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Kendaraan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.bookings.index') }}" :active="request()->routeIs('admin.bookings.*')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Bookings
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('profile.edit') }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Pengaturan
    </x-sidebar-link>
@endsection

@section('content')
    {{-- ── Welcome Banner ──────────────────────────────────── --}}
    <div class="dash-welcome-banner">
        <h2>Selamat datang, {{ auth()->user()->name }}! 👋</h2>
        <p>Berikut ringkasan sistem Renmote hari ini.</p>
    </div>

    {{-- ── Stat Cards ──────────────────────────────────────── --}}
    <div class="dash-stats">
        {{-- Users --}}
        <div class="dash-stat-card">
            <div class="dash-stat-icon blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Total Penyewa</div>
                <div class="dash-stat-value">{{ number_format($totalUsers) }}</div>
                <div class="dash-stat-sub">Pengguna terdaftar</div>
            </div>
        </div>

        {{-- Vendors --}}
        <div class="dash-stat-card">
            <div class="dash-stat-icon green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Total Vendor</div>
                <div class="dash-stat-value">{{ number_format($totalVendors) }}</div>
                <div class="dash-stat-sub">{{ $pendingVendors }} menunggu verifikasi</div>
            </div>
        </div>

        {{-- Vehicles --}}
        <div class="dash-stat-card">
            <div class="dash-stat-icon indigo">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Total Kendaraan</div>
                <div class="dash-stat-value">{{ number_format($totalVehicles) }}</div>
                <div class="dash-stat-sub">Terdaftar di platform</div>
            </div>
        </div>

        {{-- Bookings --}}
        <div class="dash-stat-card">
            <div class="dash-stat-icon purple">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Total Booking</div>
                <div class="dash-stat-value">{{ number_format($totalBookings) }}</div>
                <div class="dash-stat-sub">Transaksi tercatat</div>
            </div>
        </div>

        {{-- Pending Documents --}}
        <a href="{{ route('admin.documents.index', ['status' => 'pending']) }}" class="dash-stat-card dash-stat-card-link">
            <div class="dash-stat-icon orange">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Dokumen Pending</div>
                <div class="dash-stat-value">{{ number_format($pendingDocuments) }}</div>
                <div class="dash-stat-sub">Butuh review</div>
            </div>
        </a>
    </div>

    {{-- ── Two-column grid ─────────────────────────────────── --}}
    <div class="dash-grid dash-grid-2">

        {{-- ── Pending Vendors ─────────────────────────────── --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3 class="dash-card-title">Vendor Menunggu Verifikasi</h3>
                <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="dash-card-action">Lihat Semua →</a>
            </div>
            <div class="dash-card-body" style="padding: 0;">
                @if($pendingVendorList->count() > 0)
                  <div class="dash-table-wrap">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Toko</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingVendorList as $vendor)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600; color: #0f172a;">{{ $vendor->user->name ?? '-' }}</div>
                                        <div style="font-size: 0.75rem; color: #94a3b8;">{{ $vendor->user->email ?? '-' }}</div>
                                    </td>
                                    <td>{{ $vendor->store_name ?? '-' }}</td>
                                    <td>
                                        <span class="dash-badge warning">Pending</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  </div>
                @else
                    <div class="dash-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Semua vendor sudah diverifikasi</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Quick Actions ───────────────────────────────── --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3 class="dash-card-title">Menu Cepat</h3>
            </div>
            <div class="dash-card-body" style="padding: 8px 12px;">
                <div class="dash-action-list">
                    <a href="{{ route('admin.vendors.index') }}" class="dash-action-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Kelola Vendor
                        <svg class="action-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}" class="dash-action-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Kelola Kendaraan
                        <svg class="action-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="dash-action-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Kelola Booking
                        <svg class="action-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="dash-action-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Arsip Dokumen
                        <svg class="action-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dash-action-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Pengaturan Akun
                        <svg class="action-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Recent Users ────────────────────────────────────── --}}
    <div class="dash-card" style="margin-top: 20px;">
        <div class="dash-card-header">
            <h3 class="dash-card-title">User Terbaru</h3>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            @if($recentUsers->count() > 0)
              <div class="dash-table-wrap">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="dash-badge danger">Admin</span>
                                            @break
                                        @case('vendor')
                                            <span class="dash-badge info">Vendor</span>
                                            @break
                                        @default
                                            <span class="dash-badge success">Penyewa</span>
                                    @endswitch
                                </td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
            @else
                <div class="dash-empty">
                    <p>Belum ada user terdaftar</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Recent Bookings ─────────────────────────────────── --}}
    <div class="dash-card" style="margin-top: 20px;">
        <div class="dash-card-header">
            <h3 class="dash-card-title">Booking Terbaru</h3>
            <a href="{{ route('admin.bookings.index') }}" class="dash-card-action">Lihat Semua →</a>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            @if($recentBookings->count() > 0)
              <div class="dash-table-wrap">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Penyewa</th>
                            <th>Kendaraan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $booking->user->name ?? '-' }}</td>
                                <td>{{ $booking->vehicle->name ?? '-' }}</td>
                                <td>
                                    @switch($booking->status ?? 'pending')
                                        @case('confirmed')
                                            <span class="dash-badge success">Dikonfirmasi</span>
                                            @break
                                        @case('completed')
                                            <span class="dash-badge info">Selesai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="dash-badge danger">Dibatalkan</span>
                                            @break
                                        @default
                                            <span class="dash-badge warning">Pending</span>
                                    @endswitch
                                </td>
                                <td>{{ $booking->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
            @else
                <div class="dash-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p>Belum ada booking</p>
                </div>
            @endif
        </div>
    </div>
@endsection
