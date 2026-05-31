@props([
    'id' => null,
])

{{--
    Renmote pill-shaped primary submit button with built-in loading state.
    The js-loading-form handler in resources/js/app.js will append `is-loading`
    to the form on submit (kept consistent with auth pages today).
--}}
<button
    type="submit"
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge([
        'class' => 'group mt-2 block w-full rounded-full bg-rn-primary px-5 py-3.5 text-base font-bold text-white transition-colors duration-150 hover:bg-rn-primary-dark active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50',
    ]) }}
>
    <span class="btn-text group-[.is-loading]:hidden">{{ $slot }}</span>
    <span class="spinner mx-auto hidden h-[18px] w-[18px] animate-spin rounded-full border-2 border-white/30 border-t-white group-[.is-loading]:inline-block"></span>
</button>
