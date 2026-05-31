@extends('layouts.front')

@section('title', __('nav.contact'))

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-[1400px] px-5 pb-14 pt-12 lg:px-10" data-rn-reveal>
        <div class="relative overflow-hidden rounded-3xl px-6 py-10 sm:px-12 sm:py-14 text-center text-white">
            <img src="{{ asset('images/malang-kontak.webp') }}" alt=""
                 class="absolute inset-0 h-full w-full object-cover" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-b from-rn-text/75 via-rn-text/55 to-rn-text/35"></div>

            <div class="relative">
                <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white backdrop-blur">
                    {{ __('nav.contact') }}
                </span>
                <h1 class="mt-4 text-3xl font-extrabold text-white sm:text-4xl drop-shadow">
                    {{ app()->getLocale() === 'en' ? "Let's talk" : 'Mari kita ngobrol' }}
                </h1>
                <p class="mx-auto mt-3 max-w-2xl text-base text-white/90">
                    {{ app()->getLocale() === 'en'
                        ? 'Got a question, partnership idea, or want to be a vendor? Pick a channel that\'s easiest for you.'
                        : 'Punya pertanyaan, ide kemitraan, atau mau jadi vendor? Pilih saluran yang paling mudah buat kamu.' }}
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-[1400px] gap-5 px-5 pb-16 sm:grid-cols-2 lg:grid-cols-4 lg:px-10">
        <a href="https://wa.me/6289631926343" target="_blank" class="group rounded-2xl border border-rn-border bg-white p-6 text-center transition hover:-translate-y-1 hover:border-rn-green hover:shadow-md" data-rn-reveal>
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rn-green/10 text-rn-green">
                <i class="fab fa-whatsapp text-xl"></i>
            </div>
            <h3 class="text-sm font-bold text-rn-text">WhatsApp 1</h3>
            <p class="mt-1 text-sm text-rn-muted">+62 896-3192-6343</p>
        </a>
        <a href="https://wa.me/6289523132567" target="_blank" class="group rounded-2xl border border-rn-border bg-white p-6 text-center transition hover:-translate-y-1 hover:border-rn-green hover:shadow-md" data-rn-reveal>
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rn-green/10 text-rn-green">
                <i class="fab fa-whatsapp text-xl"></i>
            </div>
            <h3 class="text-sm font-bold text-rn-text">WhatsApp 2</h3>
            <p class="mt-1 text-sm text-rn-muted">+62 895-2313-2567</p>
        </a>
        <a href="mailto:renmotebusiness@gmail.com" class="group rounded-2xl border border-rn-border bg-white p-6 text-center transition hover:-translate-y-1 hover:border-rn-primary hover:shadow-md" data-rn-reveal>
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rn-primary/10 text-rn-primary">
                <i class="fa fa-envelope text-xl"></i>
            </div>
            <h3 class="text-sm font-bold text-rn-text">Email</h3>
            <p class="mt-1 break-all text-sm text-rn-muted">renmotebusiness@gmail.com</p>
        </a>
        <a href="https://www.instagram.com/rentalmotoronline" target="_blank" class="group rounded-2xl border border-rn-border bg-white p-6 text-center transition hover:-translate-y-1 hover:border-pink-500 hover:shadow-md" data-rn-reveal>
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-pink-50 text-pink-500">
                <i class="fab fa-instagram text-xl"></i>
            </div>
            <h3 class="text-sm font-bold text-rn-text">Instagram</h3>
            <p class="mt-1 text-sm text-rn-muted">@rentalmotoronline</p>
        </a>
    </div>
</section>
@endsection
