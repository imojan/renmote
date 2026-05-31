@extends('layouts.front')

@section('title', __('about.title'))

@section('content')
<section class="bg-white">
    {{-- Hero --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-12 pt-12 lg:px-10" data-rn-reveal>
        <div class="relative overflow-hidden rounded-3xl px-6 py-12 sm:px-12 sm:py-16 text-white">
            <img src="{{ asset('images/malang-tentangkami.webp') }}" alt=""
                 class="absolute inset-0 h-full w-full object-cover" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-r from-rn-text/80 via-rn-text/55 to-rn-text/30"></div>

            <div class="relative">
                <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white backdrop-blur">
                    {{ __('about.kicker') }}
                </span>
                <h1 class="mt-4 max-w-3xl text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl drop-shadow">
                    {{ __('about.title') }}
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/90 sm:text-lg">
                    {{ __('about.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Mission & Vision --}}
    <div class="mx-auto grid max-w-[1400px] gap-6 px-5 pb-12 lg:grid-cols-2 lg:px-10">
        <div class="rounded-2xl border border-rn-border bg-white p-7 shadow-sm transition hover:shadow-md" data-rn-reveal>
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rn-primary/10 text-rn-primary">
                <i class="fa fa-bullseye text-xl"></i>
            </div>
            <h2 class="text-xl font-bold text-rn-text">{{ __('about.mission_title') }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-rn-muted">{{ __('about.mission_text') }}</p>
        </div>
        <div class="rounded-2xl border border-rn-border bg-white p-7 shadow-sm transition hover:shadow-md" data-rn-reveal>
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rn-accent/10 text-rn-accent">
                <i class="fa fa-eye text-xl"></i>
            </div>
            <h2 class="text-xl font-bold text-rn-text">{{ __('about.vision_title') }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-rn-muted">{{ __('about.vision_text') }}</p>
        </div>
    </div>

    {{-- Values --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-12 lg:px-10" data-rn-reveal>
        <h2 class="mb-6 text-2xl font-extrabold text-rn-text sm:text-3xl">{{ __('about.values_title') }}</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @php $valueIcons = ['fa-shield-halved', 'fa-eye', 'fa-bolt', 'fa-handshake']; @endphp
            @foreach (__('about.values') as $i => $value)
                <div class="rounded-2xl border border-rn-border bg-white p-6 transition hover:-translate-y-1 hover:shadow-md" data-rn-reveal>
                    <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-rn-primary/10 text-rn-primary">
                        <i class="fa {{ $valueIcons[$i] ?? 'fa-circle' }}"></i>
                    </div>
                    <h3 class="text-base font-bold text-rn-text">{{ $value['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-rn-muted">{{ $value['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Privacy Policy --}}
    <div id="privacy" class="border-t border-rn-border bg-rn-bg" data-rn-reveal>
        <div class="mx-auto max-w-[1400px] px-5 py-14 lg:px-10">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full bg-rn-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-rn-primary">
                    {{ __('about.privacy_title') }}
                </span>
                <h2 class="mt-3 text-2xl font-extrabold text-rn-text sm:text-3xl">{{ __('about.privacy_title') }}</h2>
                <p class="mx-auto mt-2 max-w-2xl text-sm text-rn-muted">{{ __('about.privacy_subtitle') }}</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (__('about.privacy_sections') as $section)
                    <article class="rounded-2xl border border-rn-border bg-white p-6 shadow-sm" data-rn-reveal>
                        <h3 class="text-base font-bold text-rn-text">{{ $section['title'] }}</h3>
                        <ul class="mt-3 space-y-2 text-sm text-rn-muted">
                            @foreach ($section['items'] as $item)
                                <li class="flex gap-2">
                                    <i class="fa fa-check mt-1 text-xs text-rn-primary"></i>
                                    <span class="leading-relaxed">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>

            {{-- Contact privacy --}}
            <div class="mt-8 rounded-2xl border border-rn-primary/20 bg-rn-primary/[0.04] p-6 sm:p-8" data-rn-reveal>
                <h3 class="text-lg font-bold text-rn-text">{{ __('about.contact_title') }}</h3>
                <p class="mt-2 text-sm text-rn-muted">{{ __('about.contact_text') }}</p>
                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <a href="mailto:{{ __('about.contact_email') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-rn-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rn-primary-dark">
                        <i class="fa fa-envelope"></i> {{ __('about.contact_email') }}
                    </a>
                    <a href="https://wa.me/6289523132567" target="_blank"
                       class="inline-flex items-center gap-2 rounded-full border border-rn-green bg-white px-5 py-2.5 text-sm font-semibold text-rn-green transition hover:bg-rn-green hover:text-white">
                        <i class="fab fa-whatsapp"></i> {{ __('about.contact_whatsapp') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
