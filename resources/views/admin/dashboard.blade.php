@extends('layouts.dashboard')

@section('title', __('dashboard.admin.page_title'))

@section('headerActions')
    <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}"
       class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-rn-primary/40 hover:text-rn-primary">
        <i class="fa fa-bell text-xs"></i>
        {{ __('Vendor Pending') }}
        @if($pendingVendors > 0)
            <span class="ml-1 inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-amber-100 px-1.5 text-[11px] font-bold text-amber-700">{{ $pendingVendors }}</span>
        @endif
    </a>
    <a href="{{ route('admin.articles.create') }}"
       class="inline-flex items-center gap-2 rounded-full bg-rn-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rn-primary-dark">
        <i class="fa fa-plus text-xs"></i>
        {{ __('Artikel Baru') }}
    </a>
@endsection

@section('content')
    @php
        $stats = [
            [
                'label' => __('dashboard.admin.stat_total_users'),
                'value' => number_format($totalUsers),
                'icon' => 'fa-users',
                'color' => 'bg-sky-50 text-sky-600',
                'sub' => __('dashboard.admin.stat_total_users_sub'),
                'href' => route('admin.users.index'),
            ],
            [
                'label' => __('dashboard.admin.stat_total_vendors'),
                'value' => number_format($totalVendors),
                'icon' => 'fa-store',
                'color' => 'bg-emerald-50 text-emerald-600',
                'sub' => __('dashboard.admin.stat_total_vendors_sub', ['count' => $pendingVendors]),
                'href' => route('admin.vendors.index'),
            ],
            [
                'label' => __('dashboard.admin.stat_total_vehicles'),
                'value' => number_format($totalVehicles),
                'icon' => 'fa-motorcycle',
                'color' => 'bg-indigo-50 text-indigo-600',
                'sub' => __('dashboard.admin.stat_total_vehicles_sub'),
                'href' => route('admin.vehicles.index'),
            ],
            [
                'label' => __('dashboard.admin.stat_total_bookings'),
                'value' => number_format($totalBookings),
                'icon' => 'fa-clipboard-list',
                'color' => 'bg-violet-50 text-violet-600',
                'sub' => __('dashboard.admin.stat_total_bookings_sub'),
                'href' => route('admin.bookings.index'),
            ],
        ];

        $statusBadgeMap = [
            'pending'   => ['cls' => 'bg-amber-100 text-amber-700',   'label' => __('dashboard.admin.badge_pending')],
            'confirmed' => ['cls' => 'bg-emerald-100 text-emerald-700', 'label' => __('dashboard.admin.badge_confirmed')],
            'completed' => ['cls' => 'bg-sky-100 text-sky-700',        'label' => __('dashboard.admin.badge_completed')],
            'cancelled' => ['cls' => 'bg-red-100 text-red-700',        'label' => __('dashboard.admin.badge_cancelled')],
        ];
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main column --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- Welcome banner --}}
            <section class="overflow-hidden rounded-2xl bg-gradient-to-br from-rn-primary to-rn-primary-dark p-6 text-white shadow-sm sm:p-8" data-rn-reveal>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold sm:text-2xl">{{ __('dashboard.admin.welcome', ['name' => auth()->user()->name]) }}</h2>
                        <p class="mt-1 text-sm text-white/80">{{ __('dashboard.admin.welcome_subtitle') }}</p>
                    </div>
                    <div class="hidden h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 sm:flex">
                        <i class="fa fa-bolt-lightning text-2xl"></i>
                    </div>
                </div>
            </section>

            {{-- Stat grid --}}
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" data-rn-reveal>
                @foreach($stats as $stat)
                    <a href="{{ $stat['href'] }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-rn-primary/30 hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $stat['color'] }}">
                                <i class="fa-solid {{ $stat['icon'] }}"></i>
                            </div>
                            <i class="fa fa-arrow-up-right-from-square text-xs text-slate-300 transition group-hover:text-rn-primary"></i>
                        </div>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-1 text-2xl font-extrabold text-rn-text">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ $stat['sub'] }}</p>
                    </a>
                @endforeach
            </section>

            {{-- Recent bookings --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" data-rn-reveal>
                <header class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-base font-bold text-rn-text">{{ __('dashboard.admin.recent_bookings') }}</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-rn-primary hover:underline">{{ __('dashboard.admin.view_all') }}</a>
                </header>
                @if($recentBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50/80 text-left">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_renter') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_vehicle') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_status') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentBookings as $booking)
                                    @php $st = $statusBadgeMap[$booking->status ?? 'pending'] ?? $statusBadgeMap['pending']; @endphp
                                    <tr class="transition hover:bg-slate-50/60">
                                        <td class="whitespace-nowrap px-6 py-3 font-semibold text-rn-text">{{ $booking->user->name ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $booking->vehicle->name ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-3">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $st['cls'] }}">{{ $st['label'] }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-3 text-xs text-slate-500">{{ $booking->created_at->locale(app()->getLocale())->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center gap-2 px-6 py-10 text-center text-sm text-slate-500">
                        <i class="fa fa-clipboard-list text-3xl text-slate-300"></i>
                        <span>{{ __('dashboard.admin.no_bookings') }}</span>
                    </div>
                @endif
            </section>

            {{-- Recent users --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" data-rn-reveal>
                <header class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-base font-bold text-rn-text">{{ __('dashboard.admin.recent_users') }}</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-rn-primary hover:underline">{{ __('dashboard.admin.view_all') }}</a>
                </header>
                @if($recentUsers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50/80 text-left">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_name') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_email') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_role') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('dashboard.admin.col_joined') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentUsers as $user)
                                    @php
                                        $roleLabel = match ($user->role) {
                                            'admin' => ['cls' => 'bg-red-100 text-red-700', 'label' => __('dashboard.admin.role_admin')],
                                            'vendor' => ['cls' => 'bg-sky-100 text-sky-700', 'label' => __('dashboard.admin.role_vendor')],
                                            default => ['cls' => 'bg-emerald-100 text-emerald-700', 'label' => __('dashboard.admin.role_user')],
                                        };
                                    @endphp
                                    <tr class="transition hover:bg-slate-50/60">
                                        <td class="whitespace-nowrap px-6 py-3 font-semibold text-rn-text">{{ $user->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-3 text-slate-600">{{ $user->email }}</td>
                                        <td class="whitespace-nowrap px-6 py-3">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $roleLabel['cls'] }}">{{ $roleLabel['label'] }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-3 text-xs text-slate-500">{{ $user->created_at->locale(app()->getLocale())->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center gap-2 px-6 py-10 text-center text-sm text-slate-500">
                        <i class="fa fa-users text-3xl text-slate-300"></i>
                        <span>{{ __('dashboard.admin.no_users') }}</span>
                    </div>
                @endif
            </section>
        </div>

        {{-- Side column --}}
        <aside class="space-y-6">

            {{-- Pending vendors --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-rn-reveal>
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-rn-text">{{ __('dashboard.admin.pending_vendors_title') }}</h3>
                    <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-rn-primary hover:underline">{{ __('dashboard.admin.view_all') }}</a>
                </div>
                @if($pendingVendorList->count() > 0)
                    <div class="mt-4 space-y-3">
                        @foreach($pendingVendorList as $vendor)
                            <a href="{{ route('admin.vendors.show', $vendor) }}"
                               class="flex items-center gap-3 rounded-xl border border-slate-200 px-3 py-3 transition hover:border-rn-primary/40 hover:bg-rn-primary/5">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rn-primary/10 text-rn-primary">
                                    {{ strtoupper(substr($vendor->user->name ?? 'V', 0, 1)) }}
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-semibold text-rn-text">{{ $vendor->store_name ?? '-' }}</span>
                                    <span class="block truncate text-xs text-slate-500">{{ $vendor->user->name ?? '-' }}</span>
                                </span>
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">
                                    {{ __('dashboard.admin.badge_pending') }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="mt-4 flex flex-col items-center gap-2 px-2 py-6 text-center text-sm text-slate-500">
                        <i class="fa fa-check-circle text-2xl text-emerald-400"></i>
                        <span>{{ __('dashboard.admin.pending_vendors_empty') }}</span>
                    </div>
                @endif
            </section>

            {{-- Quick menu --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-rn-reveal>
                <h3 class="text-base font-bold text-rn-text">{{ __('dashboard.admin.quick_menu') }}</h3>
                <div class="mt-4 grid gap-2">
                    @php
                        $quickMenu = [
                            ['href' => route('admin.vendors.index'),   'icon' => 'fa-store',         'label' => __('dashboard.admin.quick_manage_vendors'),   'color' => 'bg-emerald-50 text-emerald-600'],
                            ['href' => route('admin.vehicles.index'),  'icon' => 'fa-motorcycle',    'label' => __('dashboard.admin.quick_manage_vehicles'),  'color' => 'bg-indigo-50 text-indigo-600'],
                            ['href' => route('admin.bookings.index'),  'icon' => 'fa-clipboard-list','label' => __('dashboard.admin.quick_manage_bookings'),  'color' => 'bg-violet-50 text-violet-600'],
                            ['href' => route('admin.documents.index'), 'icon' => 'fa-folder-open',   'label' => __('dashboard.admin.quick_documents'),        'color' => 'bg-amber-50 text-amber-600'],
                            ['href' => route('profile.edit'),          'icon' => 'fa-gear',          'label' => __('dashboard.admin.quick_settings'),         'color' => 'bg-slate-50 text-slate-600'],
                        ];
                    @endphp
                    @foreach($quickMenu as $item)
                        <a href="{{ $item['href'] }}"
                           class="flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 transition hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $item['color'] }}">
                                    <i class="fa-solid {{ $item['icon'] }} text-sm"></i>
                                </span>
                                <span class="text-sm font-semibold text-rn-text">{{ $item['label'] }}</span>
                            </span>
                            <i class="fa fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- Pending docs banner --}}
            @if($pendingDocuments > 0)
                <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5" data-rn-reveal>
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                            <i class="fa fa-folder-open"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-amber-900">{{ $pendingDocuments }} {{ __('dashboard.admin.stat_pending_documents') }}</p>
                            <p class="mt-1 text-xs text-amber-800">{{ __('dashboard.admin.stat_pending_documents_sub') }}</p>
                            <a href="{{ route('admin.documents.index', ['status' => 'pending']) }}"
                               class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-amber-900 hover:underline">
                                {{ __('dashboard.admin.view_all') }} <i class="fa fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </section>
            @endif
        </aside>
    </div>
@endsection
