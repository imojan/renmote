@props([
    'variant' => 'primary', // primary | secondary | danger | ghost
    'href' => null,
    'icon' => null,
    'size' => 'md', // sm | md
])

@php
    $sizeClass = match ($size) {
        'sm' => 'h-9 px-3.5 text-xs',
        default => 'h-10 px-5 text-sm',
    };

    $variantClass = match ($variant) {
        'primary'   => 'bg-rn-primary text-white shadow-sm hover:bg-rn-primary-dark',
        'secondary' => 'border border-slate-200 bg-white text-rn-text hover:border-rn-primary/40 hover:text-rn-primary',
        'danger'    => 'bg-red-500 text-white shadow-sm hover:bg-red-600',
        'ghost'     => 'text-slate-600 hover:bg-slate-100 hover:text-rn-text',
        default     => 'bg-rn-primary text-white shadow-sm hover:bg-rn-primary-dark',
    };

    $base = "inline-flex items-center justify-center gap-2 rounded-full font-semibold transition disabled:cursor-not-allowed disabled:opacity-50 {$sizeClass} {$variantClass}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base]) }}>
        @if($icon)<i class="fa-solid {{ $icon }} text-[12px]"></i>@endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $base]) }}>
        @if($icon)<i class="fa-solid {{ $icon }} text-[12px]"></i>@endif
        {{ $slot }}
    </button>
@endif
