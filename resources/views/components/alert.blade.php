@props(['type' => 'success'])

@php
    $classes = match($type) {
        'success' => 'bg-green-100 border border-green-400 text-green-700',
        'error' => 'bg-red-100 border border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-100 border border-blue-400 text-blue-700',
        default => 'bg-gray-100 border border-gray-400 text-gray-700',
    };
@endphp

@if(session($type) || isset($message))
    <div {{ $attributes->merge(['class' => "{$classes} px-4 py-3 rounded mb-4"]) }}>
        {{ session($type) ?? $message ?? $slot }}
    </div>
@endif
