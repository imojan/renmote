@extends('layouts.front')

@section('title', __('info.guide.title'))

@section('content')
<section class="bg-white">
    {{-- Hero --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-10 pt-12 lg:px-10" data-rn-reveal>
        <div class="relative overflow-hidden rounded-3xl px-6 py-12 sm:px-12 sm:py-16 text-white">
            {{-- Background image --}}
            <img src="{{ asset('images/malang-carasewa.jpg') }}" alt=""
                 class="absolute inset-0 h-full w-full object-cover" loading="lazy">
            {{-- Gradient overlay supaya teks tetap kontras --}}
            <div class="absolute inset-0 bg-gradient-to-r from-rn-text/80 via-rn-text/55 to-rn-text/30"></div>

            <div class="relative">
                <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white backdrop-blur">
                    {{ __('info.guide.kicker') }}
                </span>
                <h1 class="mt-4 max-w-3xl text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl drop-shadow">
                    {{ __('info.guide.title') }}
                </h1>
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-white/90 sm:text-lg">
                    {{ __('info.guide.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Steps grid --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-12 lg:px-10">
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach (__('info.guide.steps') as $step)
                <article class="rounded-2xl border border-rn-border bg-white p-6 transition hover:-translate-y-1 hover:border-rn-primary/40 hover:shadow-md" data-rn-reveal>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rn-primary/10 text-sm font-bold text-rn-primary">
                        {{ $step['no'] }}
                    </span>
                    <h3 class="mt-4 text-base font-bold text-rn-text">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-rn-muted">{{ $step['desc'] }}</p>
                </article>
            @endforeach
        </div>
    </div>

    {{-- Tips --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-12 lg:px-10" data-rn-reveal>
        <div class="rounded-2xl border border-rn-primary/20 bg-rn-primary/[0.04] p-7 sm:p-9">
            <h2 class="text-xl font-bold text-rn-text sm:text-2xl">{{ __('info.guide.tips_title') }}</h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                @foreach (__('info.guide.tips') as $tip)
                    <li class="flex gap-3 rounded-xl bg-white/60 p-3">
                        <i class="fa fa-check-circle mt-0.5 text-rn-primary"></i>
                        <span class="text-sm leading-relaxed text-rn-text">{{ $tip }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- References --}}
    <div class="mx-auto max-w-[1400px] px-5 pb-16 lg:px-10" data-rn-reveal>
        <div class="rounded-2xl border border-rn-border bg-rn-bg p-7">
            <h2 class="text-lg font-bold text-rn-text">{{ __('info.guide.reference_title') }}</h2>
            <p class="mt-2 text-sm leading-relaxed text-rn-muted">{{ __('info.guide.reference_text') }}</p>
            <ul class="mt-3 space-y-1.5 text-sm">
                <li><a href="https://peraturan.bpk.go.id/Details/38654/uu-no-22-tahun-2009" target="_blank" rel="noopener" class="text-rn-primary underline-offset-2 hover:underline">{{ __('info.guide.ref_uu_2009') }}</a></li>
                <li><a href="https://korlantas.polri.go.id/sim/" target="_blank" rel="noopener" class="text-rn-primary underline-offset-2 hover:underline">{{ __('info.guide.ref_korlantas') }}</a></li>
                <li><a href="https://jdih.dephub.go.id/" target="_blank" rel="noopener" class="text-rn-primary underline-offset-2 hover:underline">{{ __('info.guide.ref_jdih') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
