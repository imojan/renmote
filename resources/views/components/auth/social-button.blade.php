@props([
    'href' => '#',
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'mt-2.5 flex w-full items-center justify-center gap-3 rounded-full border-[1.5px] border-gray-300 bg-white px-5 py-3 text-[0.9375rem] font-semibold text-rn-text transition-colors duration-150 first:mt-0 hover:border-gray-400 hover:bg-gray-50',
    ]) }}
>
    {{ $slot }}
</a>
