@props([
    'label' => null,
    'for' => null,
    'error' => null,
])

{{--
    Renmote auth field wrapper: label + slot input + (optional) inline error.
    Replaces .auth-field block.
--}}
<div {{ $attributes->merge(['class' => 'mb-[18px]']) }}>
    @if($label)
        <label @if($for) for="{{ $for }}" @endif
               class="mb-1.5 block text-sm font-semibold text-rn-text">
            {{ $label }}
        </label>
    @endif

    {{ $slot }}

    @if($error)
        <div class="mt-1 text-[0.8125rem] text-red-500">{{ $error }}</div>
    @endif
</div>
