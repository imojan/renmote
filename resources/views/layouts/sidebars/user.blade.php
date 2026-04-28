@php
    $userUnreadChats = \App\Models\Conversation::where('user_id', auth()->id())->sum('unread_user_count');
    $userUnreadNotifications = auth()->user()->unreadNotifications()->count();
@endphp

<x-sidebar-link href="{{ route('user.dashboard') }}" :active="request()->routeIs('user.dashboard')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Dashboard
</x-sidebar-link>

<x-sidebar-link href="{{ route('user.bookings.index') }}" :active="request()->routeIs('user.bookings.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
    Booking
</x-sidebar-link>

<x-sidebar-link href="{{ route('user.addresses.index') }}" :active="request()->routeIs('user.addresses.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Alamat
</x-sidebar-link>

<x-sidebar-link href="{{ route('chat.index') }}" :active="request()->routeIs('chat.*')">
    <i class="fa-solid fa-comments w-5 h-5 mr-3" aria-hidden="true"></i>
    Chat
    @if($userUnreadChats > 0)
        <span class="dash-nav-badge">{{ $userUnreadChats }}</span>
    @endif
</x-sidebar-link>

<x-sidebar-link href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications.*')">
    <i class="fa-solid fa-bell w-5 h-5 mr-3" aria-hidden="true"></i>
    Notifikasi
    @if($userUnreadNotifications > 0)
        <span class="dash-nav-badge">{{ $userUnreadNotifications }}</span>
    @endif
</x-sidebar-link>

<x-sidebar-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    Profil
</x-sidebar-link>
