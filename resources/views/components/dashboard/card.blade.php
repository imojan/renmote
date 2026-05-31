@props([
    'title' => null,
    'subtitle' => null,
    'padded' => true,
])

<section {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white shadow-sm']) }}>
    @if($title || isset($actions))
        <header class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
            <div>
                @if($title)
                    <h3 class="text-base font-bold text-rn-text">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="mt-0.5 text-xs text-slate-500">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </header>
    @endif

    <div @class([
        'p-6' => $padded,
    ])>
        {{ $slot }}
    </div>
</section>
