@props([
    'variant' => 'topbar', // topbar | mobile
])

@php
    $current = app()->getLocale();
    $isTopbar = $variant === 'topbar';
@endphp

<div {{ $attributes->merge(['class' => $isTopbar
    ? 'rn-locale-switch flex items-center gap-1 text-[12px] font-semibold'
    : 'flex items-center gap-2 text-sm font-semibold text-white']) }}>
    <i class="fa fa-globe {{ $isTopbar ? 'text-white' : '' }}"></i>
    <a href="{{ route('locale.switch', 'en') }}"
       class="rn-locale-link px-1 transition-colors {{ $current === 'en' ? 'is-active' : '' }}">
        EN
    </a>
    <span class="rn-locale-divider">|</span>
    <a href="{{ route('locale.switch', 'id') }}"
       class="rn-locale-link px-1 transition-colors {{ $current === 'id' ? 'is-active' : '' }}">
        ID
    </a>
</div>
