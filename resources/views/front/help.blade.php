@extends('layouts.front')

@section('title', __('help.title'))

@section('content')
<section class="bg-white" x-data="{ tab: 'renter' }">
    <div class="mx-auto max-w-[1400px] px-5 pt-12 lg:px-10" data-rn-reveal>
        {{-- Header --}}
        <div class="rounded-2xl border border-rn-border bg-white p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-rn-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-rn-primary">
                        {{ __('help.kicker') }}
                    </span>
                    <h1 class="mt-3 text-3xl font-extrabold text-rn-text sm:text-4xl">{{ __('help.title') }}</h1>
                    <p class="mt-2 max-w-2xl text-sm text-rn-muted sm:text-base">{{ __('help.subtitle') }}</p>
                </div>

                {{-- Toggle --}}
                <div class="inline-flex shrink-0 items-center gap-1 rounded-full border border-rn-border bg-rn-bg p-1 self-start">
                    <button type="button" @click="tab = 'renter'"
                            :class="tab === 'renter' ? 'bg-rn-primary text-white shadow-sm' : 'text-rn-muted'"
                            class="rounded-full px-4 py-2 text-sm font-semibold transition">
                        {{ __('help.tab_renter') }}
                    </button>
                    <button type="button" @click="tab = 'vendor'"
                            :class="tab === 'vendor' ? 'bg-rn-primary text-white shadow-sm' : 'text-rn-muted'"
                            class="rounded-full px-4 py-2 text-sm font-semibold transition">
                        {{ __('help.tab_vendor') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-[1400px] gap-6 px-5 py-10 lg:grid-cols-12 lg:px-10">

        {{-- Main FAQ groups --}}
        <div class="lg:col-span-8 space-y-6">
            @foreach (['renter', 'vendor'] as $key)
                <div x-show="tab === '{{ $key }}'" x-cloak class="space-y-6">
                    @foreach (__('help.'.$key.'_groups') as $groupIdx => $group)
                        <article class="rounded-2xl border border-rn-border bg-white shadow-sm" data-rn-reveal>
                            <header class="rounded-t-2xl bg-rn-bg px-6 py-4">
                                <h2 class="text-lg font-bold text-rn-text">{{ $group['title'] }}</h2>
                            </header>
                            <div class="divide-y divide-rn-border">
                                @foreach ($group['items'] as $itemIdx => $item)
                                    <details class="group px-6 py-4">
                                        <summary class="flex cursor-pointer items-center justify-between gap-4 text-sm font-semibold text-rn-text marker:hidden">
                                            <span>{{ $item['q'] }}</span>
                                            <i class="fa fa-chevron-down text-xs text-rn-muted transition group-open:rotate-180"></i>
                                        </summary>
                                        <p class="mt-3 text-sm leading-relaxed text-rn-muted">{{ $item['a'] }}</p>
                                    </details>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4">
            <div class="sticky top-6 space-y-4">
                <div class="rounded-2xl border border-rn-border bg-white p-6 text-center shadow-sm" data-rn-reveal>
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-rn-primary/10 text-rn-primary">
                        <i class="fa fa-headset text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-rn-text">{{ __('help.sidebar_title') }}</h3>
                    <p class="mt-1 text-sm text-rn-muted">{{ __('help.sidebar_subtitle') }}</p>

                    <div class="mt-5 space-y-3 text-left">
                        <a href="https://wa.me/6289631926343" target="_blank"
                           class="flex items-center gap-3 rounded-xl bg-rn-green px-4 py-3 text-white transition hover:bg-rn-green-dark">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </div>
                            <div class="leading-tight">
                                <div class="text-sm font-bold">{{ __('help.whatsapp_label') }} 1</div>
                                <div class="text-xs opacity-90">+62 896-3192-6343</div>
                            </div>
                        </a>
                        <a href="https://wa.me/6289523132567" target="_blank"
                           class="flex items-center gap-3 rounded-xl bg-rn-green px-4 py-3 text-white transition hover:bg-rn-green-dark">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </div>
                            <div class="leading-tight">
                                <div class="text-sm font-bold">{{ __('help.whatsapp_label') }} 2</div>
                                <div class="text-xs opacity-90">+62 895-2313-2567</div>
                            </div>
                        </a>
                        <a href="mailto:renmotebusiness@gmail.com"
                           class="flex items-center gap-3 rounded-xl bg-rn-primary px-4 py-3 text-white transition hover:bg-rn-primary-dark">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15">
                                <i class="fa fa-envelope text-lg"></i>
                            </div>
                            <div class="leading-tight">
                                <div class="text-sm font-bold">{{ __('help.email_label') }}</div>
                                <div class="text-xs opacity-90">renmotebusiness@gmail.com</div>
                            </div>
                        </a>
                    </div>

                    <div class="mt-6 border-t border-rn-border pt-5 text-left">
                        <p class="text-xs font-semibold uppercase tracking-wider text-rn-muted">{{ __('help.hours_label') }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rn-green/60"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-rn-green"></span>
                            </span>
                            <span class="text-base font-bold text-rn-text">{{ __('help.hours_value') }}</span>
                        </div>
                        <p class="mt-1 text-xs text-rn-muted">{{ __('help.hours_subtitle') }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5" data-rn-reveal>
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                            <i class="fa fa-lightbulb"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-amber-900">{{ __('help.tip_title') }}</h4>
                            <p class="mt-1 text-xs leading-relaxed text-amber-800">{{ __('help.tip_message') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection
