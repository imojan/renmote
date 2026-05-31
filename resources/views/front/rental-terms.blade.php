@extends('layouts.front')

@section('title', __('info.terms.title'))

@section('content')
<section class="bg-white" x-data="{ tab: 'renter' }">
    <div class="mx-auto max-w-[1400px] px-5 pb-10 pt-12 lg:px-10" data-rn-reveal>
        <div class="relative overflow-hidden rounded-3xl px-6 py-12 sm:px-12 sm:py-14 text-white">
            <img src="{{ asset('images/malang-sdank.jpg') }}" alt=""
                 class="absolute inset-0 h-full w-full object-cover" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-r from-rn-text/85 via-rn-text/60 to-rn-text/30"></div>

            <div class="relative">
                <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white backdrop-blur">
                    {{ __('info.terms.kicker') }}
                </span>
                <h1 class="mt-4 max-w-3xl text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl drop-shadow">
                    {{ __('info.terms.title') }}
                </h1>
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-white/90 sm:text-lg">
                    {{ __('info.terms.subtitle') }}
                </p>

                <div class="mt-6 inline-flex items-center gap-1 rounded-full border border-white/30 bg-white/15 p-1 backdrop-blur">
                    <button type="button" @click="tab = 'renter'"
                            :class="tab === 'renter' ? 'bg-white text-rn-primary shadow-sm' : 'text-white/90 hover:text-white'"
                            class="rounded-full px-5 py-2 text-sm font-semibold transition">
                        {{ __('info.terms.tab_renter') }}
                    </button>
                    <button type="button" @click="tab = 'vendor'"
                            :class="tab === 'vendor' ? 'bg-white text-rn-primary shadow-sm' : 'text-white/90 hover:text-white'"
                            class="rounded-full px-5 py-2 text-sm font-semibold transition">
                        {{ __('info.terms.tab_vendor') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Sections --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-12 lg:px-10">
        @foreach (['renter', 'vendor'] as $key)
            <div x-show="tab === '{{ $key }}'" x-cloak class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach (__('info.terms.'.$key.'_sections') as $section)
                    <article class="rounded-2xl border border-rn-border bg-white p-6 transition hover:-translate-y-1 hover:border-rn-primary/40 hover:shadow-md" data-rn-reveal>
                        <h3 class="text-base font-bold text-rn-text">{{ $section['title'] }}</h3>
                        <ul class="mt-3 space-y-2">
                            @foreach ($section['items'] as $item)
                                <li class="flex gap-2 text-sm text-rn-muted">
                                    <i class="fa fa-circle mt-2 text-[5px] text-rn-primary"></i>
                                    <span class="leading-relaxed">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- References --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-16 lg:px-10" data-rn-reveal>
        <div class="rounded-2xl border border-rn-border bg-rn-bg p-7">
            <h2 class="text-lg font-bold text-rn-text">{{ __('info.terms.reference_title') }}</h2>
            <p class="mt-2 text-sm leading-relaxed text-rn-muted">{{ __('info.terms.reference_text') }}</p>
            <ul class="mt-3 space-y-1.5 text-sm">
                <li><a href="https://peraturan.bpk.go.id/Details/38654/uu-no-22-tahun-2009" target="_blank" rel="noopener" class="text-rn-primary underline-offset-2 hover:underline">{{ __('info.terms.ref_uu_2009') }}</a></li>
                <li><a href="https://korlantas.polri.go.id/sim/" target="_blank" rel="noopener" class="text-rn-primary underline-offset-2 hover:underline">{{ __('info.terms.ref_korlantas') }}</a></li>
                <li><a href="https://jdih.dephub.go.id/" target="_blank" rel="noopener" class="text-rn-primary underline-offset-2 hover:underline">{{ __('info.terms.ref_jdih') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
