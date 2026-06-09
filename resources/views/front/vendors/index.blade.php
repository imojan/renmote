@extends('layouts.front')

@section('title', __('Daftar Vendor'))

@section('content')
<section class="section front-content-section">
    <div class="article-list-head">
        <div class="article-list-intro">
            <h1 class="section-title">{{ __('Vendor Andalan Renmote') }}</h1>
            <p class="article-list-subtitle">
                {{ __('Vendor terverifikasi yang menyewakan motor di area Malang dan sekitarnya. Pilih vendor sesuai lokasi dan koleksi motornya.') }}
            </p>
        </div>
        <form method="GET" action="{{ route('vendors.index') }}" class="article-list-search">
            <input type="text" name="q" value="{{ $keyword }}" placeholder="{{ __('Cari vendor atau lokasi...') }}" autocomplete="off">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    @if($vendors->total() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($vendors as $vendor)
                <a href="{{ route('vendors.show', $vendor) }}"
                   class="group flex flex-col rounded-2xl border border-rn-border bg-white overflow-hidden transition hover:-translate-y-1 hover:border-rn-primary/40 hover:shadow-lg">
                    <div class="relative h-44 overflow-hidden bg-gradient-to-br from-rn-primary/10 via-white to-rn-accent/10">
                        @if($vendor->cover_photo)
                            <img src="{{ Storage::url($vendor->cover_photo) }}" alt="{{ $vendor->store_name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <i class="fa fa-store text-5xl text-rn-primary/50"></i>
                            </div>
                        @endif
                        @if($vendor->verified)
                            <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full bg-rn-primary px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                                <i class="fa fa-check-circle"></i> {{ __('Verified') }}
                            </span>
                        @endif

                        @auth
                            @if(auth()->user()->role === 'user')
                                <form action="{{ route('user.wishlist.vendors.toggle', $vendor) }}" method="POST"
                                      class="absolute top-3 right-3"
                                      data-rn-wishlist
                                      onclick="event.stopPropagation();">
                                    @csrf
                                    @php $isWishlisted = in_array($vendor->id, $wishlistedVendorIds ?? [], true); @endphp
                                    <button type="submit"
                                            class="btn-fav flex h-9 w-9 items-center justify-center rounded-full border bg-white/95 transition {{ $isWishlisted ? 'is-active border-red-300 text-red-500' : 'border-gray-200 text-gray-400 hover:border-red-300 hover:text-red-500' }}">
                                        <i class="fa fa-heart"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-start gap-4">
                            <div class="h-16 w-16 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shrink-0">
                                @if($vendor->profile_photo)
                                    <img src="{{ Storage::url($vendor->profile_photo) }}" alt="{{ $vendor->store_name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-red-100 font-bold text-red-600 text-lg">
                                        {{ strtoupper(substr($vendor->store_name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-rn-text line-clamp-1">{{ $vendor->store_name }}</h3>
                                <p class="mt-1 flex items-center gap-1.5 text-sm text-rn-muted">
                                    <i class="fa fa-map-marker-alt text-xs"></i>
                                    {{ $vendor->district?->name ?? __('Lokasi belum diatur') }}
                                </p>
                                @if($vendor->rating)
                                    <p class="mt-1.5 flex items-center gap-1 text-sm font-semibold text-rn-text">
                                        <i class="fa fa-star text-amber-400"></i>
                                        {{ number_format($vendor->rating, 1) }}
                                        <span class="text-xs font-normal text-rn-muted">({{ number_format($vendor->rating_count ?? 0) }})</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($vendor->description)
                            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-rn-muted">
                                {{ $vendor->description }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center justify-between border-t border-rn-border pt-4">
                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-rn-text">
                                <i class="fa fa-motorcycle text-rn-primary"></i>
                                {{ $vendor->vehicles->count() }} {{ __('motor') }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-rn-primary group-hover:underline">
                                {{ __('Lihat Detail') }} <i class="fa fa-arrow-right text-xs"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($vendors->hasPages())
            <div class="mt-10">{{ $vendors->links() }}</div>
        @endif
    @else
        <div class="rounded-2xl border border-rn-border bg-white p-10 text-center">
            <i class="fa fa-store text-4xl text-rn-muted/50"></i>
            <p class="mt-3 text-sm text-rn-muted">{{ __('Belum ada vendor yang terdaftar.') }}</p>
        </div>
    @endif
</section>
@endsection
