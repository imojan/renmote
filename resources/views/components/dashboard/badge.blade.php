@props([
    'tone' => 'slate', // slate | success | warning | danger | info | primary
])

@php
    $cls = match ($tone) {
        'success' => 'bg-emerald-100 text-emerald-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'danger'  => 'bg-red-100 text-red-700',
        'info'    => 'bg-sky-100 text-sky-700',
        'primary' => 'bg-rn-primary/10 text-rn-primary',
        default   => 'bg-slate-100 text-slate-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold {$cls}"]) }}>
    {{ $slot }}
</span>
