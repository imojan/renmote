@props([
    'id',
    'name' => 'password',
])

{{--
    Renmote auth password input + visibility toggle.
    Uses the global js-password-toggle handler wired up in resources/js/app.js,
    so no inline onclick is needed.
--}}
<div class="relative">
    <input
        id="{{ $id }}"
        type="password"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-3.5 py-3 pr-12 text-[0.9375rem] text-rn-text placeholder:text-gray-400 transition-colors duration-150 focus:border-rn-primary focus:outline-none focus:ring-[3px] focus:ring-rn-primary/15',
        ]) }}
    >
    <button
        type="button"
        class="js-password-toggle absolute right-3 top-1/2 flex -translate-y-1/2 items-center justify-center bg-transparent p-1 text-gray-500 hover:text-gray-700"
        data-target="{{ $id }}"
        aria-label="Tampilkan password"
    >
        <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
        <svg class="eye-closed hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
        </svg>
    </button>
</div>
