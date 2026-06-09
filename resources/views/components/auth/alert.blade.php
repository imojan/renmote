@props([
    'type' => 'success',
])

@php
    $config = [
        'success' => [
            'bg'    => 'bg-emerald-50/80 border-emerald-200',
            'icon'  => 'text-white bg-emerald-500',
            'title' => 'text-emerald-900',
            'text'  => 'text-emerald-700/80',
            'close' => 'text-emerald-400 hover:text-emerald-600 hover:bg-emerald-100',
            'svg'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />',
        ],
        'error' => [
            'bg'    => 'bg-red-50/80 border-red-200',
            'icon'  => 'text-white bg-red-500',
            'title' => 'text-red-900',
            'text'  => 'text-red-700/80',
            'close' => 'text-red-400 hover:text-red-600 hover:bg-red-100',
            'svg'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />',
        ],
        'warning' => [
            'bg'    => 'bg-amber-50/80 border-amber-200',
            'icon'  => 'text-white bg-amber-400',
            'title' => 'text-amber-900',
            'text'  => 'text-amber-700/80',
            'close' => 'text-amber-400 hover:text-amber-600 hover:bg-amber-100',
            'svg'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />',
        ],
        'info' => [
            'bg'    => 'bg-blue-50/80 border-blue-200',
            'icon'  => 'text-white bg-blue-500',
            'title' => 'text-blue-900',
            'text'  => 'text-blue-700/80',
            'close' => 'text-blue-400 hover:text-blue-600 hover:bg-blue-100',
            'svg'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />',
        ],
    ];
    $c = $config[$type] ?? $config['success'];
@endphp

<div {{ $attributes->merge(['class' => "mb-5 rounded-2xl border px-4 py-3.5 {$c['bg']}"]) }}>
    <div class="flex items-start gap-3">
        {{-- Icon --}}
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $c['icon'] }}">
            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                {!! $c['svg'] !!}
            </svg>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0 pt-0.5 text-[0.8125rem] font-medium leading-relaxed {{ $c['text'] }}">
            {{ $slot }}
        </div>
    </div>
</div>
