@props([
    'subtitle' => null,
])

{{--
    Renmote auth heading: title (Montserrat 2rem extrabold) + optional subtitle.
    Matches the previous .auth-title / .auth-subtitle styles 1:1.
--}}
<h1 {{ $attributes->merge(['class' => 'mb-2 text-center text-[2rem] font-extrabold leading-[1.2] tracking-[-0.02em] text-rn-text font-montserrat']) }}>
    {{ $slot }}
</h1>

@if($subtitle)
    <p class="mb-8 text-center text-sm text-gray-500">{{ $subtitle }}</p>
@endif
