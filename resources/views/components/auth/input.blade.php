@props([
    'type' => 'text',
])

{{--
    Renmote auth input. Mirror of the previous .auth-field input style:
    full-width 12px/14px padding, 1.5px border, focus ring tinted with brand blue.
--}}
<input
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'block w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-3.5 py-3 text-[0.9375rem] text-rn-text placeholder:text-gray-400 transition-colors duration-150 focus:border-rn-primary focus:outline-none focus:ring-[3px] focus:ring-rn-primary/15',
    ]) }}
>
