@props([
    'action',
    'method' => 'GET',
])

{{--
    Reusable filter strip used across dashboard list pages.
    Children form a CSS-grid with auto-fit columns so every field
    plus the trailing buttons share equal width and consistent height.
--}}
<form action="{{ $action }}" method="{{ $method }}"
      class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
        {{ $slot }}
    </div>

    @isset($actions)
        <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
            {{ $actions }}
        </div>
    @endisset
</form>
