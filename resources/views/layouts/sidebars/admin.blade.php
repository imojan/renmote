@php
    $pendingVendors = \App\Models\Vendor::where('status', 'pending')->count();
    $pendingUserDocuments = \App\Models\UserDocument::where('status', 'pending')->count();
    $pendingVendorDocuments = \App\Models\VendorDocument::where('status', 'pending')->count();
    $pendingAllDocuments = $pendingUserDocuments + $pendingVendorDocuments;
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
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h9l5 5v9a2 2 0 01-2 2z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-8H7v8"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h1m4 0h1"/>
    </svg>
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
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317a1 1 0 011.35-.936l.108.043 1.49.744a1 1 0 00.894 0l1.49-.744a1 1 0 011.458.893v1.658a1 1 0 00.553.894l1.49.744a1 1 0 01.36 1.57l-1.06 1.325a1 1 0 000 1.249l1.06 1.325a1 1 0 01-.36 1.57l-1.49.744a1 1 0 00-.553.894v1.658a1 1 0 01-1.458.893l-1.49-.744a1 1 0 00-.894 0l-1.49.744a1 1 0 01-1.458-.893v-1.658a1 1 0 00-.553-.894l-1.49-.744a1 1 0 01-.36-1.57l1.06-1.325a1 1 0 000-1.249L3.8 9.146a1 1 0 01.36-1.57l1.49-.744a1 1 0 00.553-.894V4.28a1 1 0 011.458-.893l1.49.744a1 1 0 00.894 0l1.49-.744z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Pengaturan
</x-sidebar-link>
