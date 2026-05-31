@php
    $brand = config('app.name', 'Renmote');
@endphp

{{-- Soft CTA strip above the footer --}}
<section class="bg-rn-bg" data-rn-reveal>
    <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-4 px-5 py-8 text-center sm:flex-row sm:text-left lg:px-10">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rn-green/10 text-rn-green">
                <i class="fab fa-whatsapp text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-rn-text sm:text-lg">{{ __('footer.cta_title') }}</h3>
                <p class="text-sm text-rn-muted">{{ __('footer.cta_subtitle') }}</p>
            </div>
        </div>
        <a href="https://wa.me/6289631926343" target="_blank"
           class="inline-flex items-center gap-2 rounded-full bg-rn-green px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rn-green-dark">
            <i class="fab fa-whatsapp text-base"></i>
            {{ __('footer.cta_button') }}
        </a>
    </div>
</section>

<footer class="border-t border-rn-border bg-white" data-rn-reveal>
    <div class="mx-auto max-w-[1400px] px-5 py-12 lg:px-10">
        <div class="grid gap-10 md:grid-cols-12">

            {{-- Brand block --}}
            <div class="md:col-span-4">
                <a href="{{ route('home') }}" class="inline-flex items-center">
                    <img src="{{ asset('images/renmote-biru.png') }}" alt="{{ $brand }}" class="h-19 w-auto">
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-rn-muted">
                    {{ __('footer.tagline') }}
                </p>

                <div class="mt-5 flex items-center gap-3">
                    <a href="https://wa.me/6289523132567" target="_blank"
                       aria-label="{{ __('footer.social_whatsapp') }}"
                       class="flex h-9 w-9 items-center justify-center rounded-full bg-rn-primary transition hover:bg-rn-primary-dark">
                        <img src="{{ asset('images/wa-white.png') }}" alt="" class="h-4 w-4">
                    </a>
                    <a href="https://www.tiktok.com/@rentalmotoronline" target="_blank"
                       aria-label="{{ __('footer.social_tiktok') }}"
                       class="flex h-9 w-9 items-center justify-center rounded-full bg-rn-primary transition hover:bg-rn-primary-dark">
                        <img src="{{ asset('images/tiktok-white.png') }}" alt="" class="h-4 w-4">
                    </a>
                    <a href="https://www.instagram.com/rentalmotoronline" target="_blank"
                       aria-label="{{ __('footer.social_instagram') }}"
                       class="flex h-9 w-9 items-center justify-center rounded-full bg-rn-primary transition hover:bg-rn-primary-dark">
                        <img src="{{ asset('images/instagram-white.png') }}" alt="" class="h-4 w-4">
                    </a>
                    <a href="mailto:renmotebusiness@gmail.com"
                       aria-label="{{ __('footer.social_email') }}"
                       class="flex h-9 w-9 items-center justify-center rounded-full bg-rn-primary transition hover:bg-rn-primary-dark">
                        <img src="{{ asset('images/gmail-white.png') }}" alt="" class="h-4 w-4">
                    </a>
                </div>
            </div>

            {{-- Explore --}}
            <div class="md:col-span-3">
                <h4 class="text-sm font-bold uppercase tracking-wider text-rn-text">{{ __('footer.col_explore') }}</h4>
                <ul class="mt-4 space-y-2.5 text-sm text-rn-muted">
                    <li><a href="{{ route('home') }}" class="transition hover:text-rn-primary">{{ __('footer.item_home') }}</a></li>
                    <li><a href="{{ route('articles.index') }}" class="transition hover:text-rn-primary">{{ __('footer.item_articles') }}</a></li>
                    <li><a href="{{ route('rent.guide') }}" class="transition hover:text-rn-primary">{{ __('footer.item_rent_guide') }}</a></li>
                    <li><a href="{{ route('rent.terms') }}" class="transition hover:text-rn-primary">{{ __('footer.item_rent_terms') }}</a></li>
                </ul>
            </div>

            {{-- Company --}}
            <div class="md:col-span-2">
                <h4 class="text-sm font-bold uppercase tracking-wider text-rn-text">{{ __('footer.col_company') }}</h4>
                <ul class="mt-4 space-y-2.5 text-sm text-rn-muted">
                    <li><a href="{{ route('about') }}" class="transition hover:text-rn-primary">{{ __('footer.item_about') }}</a></li>
                    <li><a href="{{ route('help') }}" class="transition hover:text-rn-primary">{{ __('footer.item_help') }}</a></li>
                </ul>
            </div>

            {{-- Join --}}
            <div class="md:col-span-3">
                <h4 class="text-sm font-bold uppercase tracking-wider text-rn-text">{{ __('footer.col_join') }}</h4>
                <ul class="mt-4 space-y-2.5 text-sm text-rn-muted">
                    <li><a href="{{ route('register') }}" class="transition hover:text-rn-primary">{{ __('footer.item_become_vendor') }}</a></li>
                    <li><a href="{{ route('login') }}" class="transition hover:text-rn-primary">{{ __('footer.item_login') }}</a></li>
                </ul>

                <div class="mt-5 space-y-2 text-sm text-rn-muted">
                    <a href="https://wa.me/6289523132567" target="_blank" class="flex items-center gap-2 transition hover:text-rn-primary">
                        <i class="fab fa-whatsapp text-rn-green"></i>
                        <span>0895 2313 2567 — {{ __('footer.consultation') }}</span>
                    </a>
                    <a href="mailto:renmotebusiness@gmail.com" class="flex items-center gap-2 transition hover:text-rn-primary">
                        <i class="fa fa-envelope text-rn-primary"></i>
                        <span>renmotebusiness@gmail.com</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-rn-border">
        <div class="mx-auto flex max-w-[1400px] flex-col items-center justify-between gap-2 px-5 py-5 text-xs text-rn-muted sm:flex-row lg:px-10">
            <span>&copy; {{ date('Y') }} {!! __('footer.copyright', ['brand' => '<strong class="text-rn-text">'.$brand.' Motorcycle Rental</strong>']) !!}</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('rent.terms') }}" class="transition hover:text-rn-primary">{{ __('footer.item_rent_terms') }}</a>
                <span class="opacity-50">·</span>
                <a href="{{ route('about') }}" class="transition hover:text-rn-primary">{{ __('about.privacy_title') }}</a>
            </div>
        </div>
    </div>
</footer>
