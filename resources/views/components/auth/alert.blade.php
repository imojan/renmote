@props([
    'type' => 'success',
])

@php
    $variants = [
        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'error'   => 'bg-red-50 text-red-800 border-red-200',
    ];
    $variant = $variants[$type] ?? $variants['success'];
@endphp

<div {{ $attributes->merge(['class' => "mb-5 rounded-lg border px-4 py-3 text-[0.8125rem] font-medium {$variant}"]) }}>
    {{ $slot }}
</div>
