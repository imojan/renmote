@props(['status', 'type' => 'booking'])

@php
    $classes = match($status) {
        'pending' => 'bg-yellow-100 text-yellow-800',
        'confirmed', 'approved' => 'bg-green-100 text-green-800',
        'completed' => 'bg-blue-100 text-blue-800',
        'cancelled', 'rejected' => 'bg-red-100 text-red-800',
        'available' => 'bg-green-100 text-green-800',
        'unavailable' => 'bg-red-100 text-red-800',
        'paid' => 'bg-green-100 text-green-800',
        default => 'bg-gray-100 text-gray-800',
    };
    
    $label = ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "px-2 py-1 text-xs font-medium rounded-full {$classes}"]) }}>
    {{ $label }}
</span>
