@php
    $pendingVendors = \App\Models\Vendor::where('status', 'pending')->count();
    $pendingUserDocuments = \App\Models\UserDocument::where('status', 'pending')->count();
    $pendingVendorDocuments = \App\Models\VendorDocument::where('status', 'pending')->count();
    $pendingAllDocuments = $pendingUserDocuments + $pendingVendorDocuments;
    $usersLastSeenAt = session('admin_users_last_seen_at');
    $newUsersCountQuery = \App\Models\User::where('role', 'user');

    if ($usersLastSeenAt) {
        $newUsersCountQuery->where('created_at', '>', $usersLastSeenAt);
    }

    $newUsersCount = $newUsersCountQuery->count();
@endphp

<x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Dashboard
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.vendors.index') }}" :active="request()->routeIs('admin.vendors.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
    </svg>
    Vendors
    @if($pendingVendors > 0)
        <span class="dash-nav-badge">{{ $pendingVendors }}</span>
    @endif
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
    <i class="fa-regular fa-id-card w-5 h-5 mr-3" aria-hidden="true"></i>
    Users
    @if($newUsersCount > 0)
        <span class="dash-nav-badge">{{ $newUsersCount }}</span>
    @endif
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.vehicles.index') }}" :active="request()->routeIs('admin.vehicles.*')">
    <i class="fa fa-motorcycle w-5 h-5 mr-3" aria-hidden="true"></i>
    Kendaraan
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.bookings.index') }}" :active="request()->routeIs('admin.bookings.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
    Bookings
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.articles.index') }}" :active="request()->routeIs('admin.articles.*')">
    <i class="fa-regular fa-newspaper w-5 h-5 mr-3" aria-hidden="true"></i>
    Artikel
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.documents.index') }}" :active="request()->routeIs('admin.documents.*')">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    Dokumen
    @if($pendingAllDocuments > 0)
        <span class="dash-nav-badge">{{ $pendingAllDocuments }}</span>
    @endif
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">
    <i class="fa-solid fa-gear w-5 h-5 mr-3" aria-hidden="true"></i>
    Pengaturan
</x-sidebar-link>
