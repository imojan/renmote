@props(['value', 'label', 'color' => 'blue'])

@php
    $colors = [
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'red' => 'bg-red-500',
        'purple' => 'bg-purple-500',
        'indigo' => 'bg-indigo-500',
    ];
    $bgColor = $colors[$color] ?? 'bg-blue-500';
@endphp

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center">
        <div class="{{ $bgColor }} rounded-full p-3">
            {{ $slot }}
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>
