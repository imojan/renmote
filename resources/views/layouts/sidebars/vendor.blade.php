@php
    $vendorId = optional(auth()->user()->vendor)->id;
    $vendorUnreadChats = $vendorId
        ? \App\Models\Conversation::where('vendor_id', $vendorId)->sum('unread_vendor_count')
        : 0;
@endphp

<x-sidebar-link href="{{ route('vendor.dashboard') }}" :active="request()->routeIs('vendor.dashboard')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Dashboard
</x-sidebar-link>

<x-sidebar-link href="{{ route('vendor.vehicles.index') }}" :active="request()->routeIs('vendor.vehicles.*')">
    <i class="fa fa-motorcycle w-5 h-5 mr-3" aria-hidden="true"></i>
    Kendaraan
</x-sidebar-link>

<x-sidebar-link href="{{ route('vendor.bookings.index') }}" :active="request()->routeIs('vendor.bookings.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
    Pesanan
</x-sidebar-link>

<x-sidebar-link href="{{ route('chat.index') }}" :active="request()->routeIs('chat.*')">
    <i class="fa-solid fa-comments w-5 h-5 mr-3" aria-hidden="true"></i>
    Chat Pelanggan
    @if($vendorUnreadChats > 0)
        <span class="dash-nav-badge">{{ $vendorUnreadChats }}</span>
    @endif
</x-sidebar-link>

<x-sidebar-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    Profil
</x-sidebar-link>
