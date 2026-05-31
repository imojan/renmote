@props([
    'title',
    'subtitle' => null,
])

<div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-rn-text sm:text-3xl">{{ $title }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $subtitle ?? now()->locale(app()->getLocale())->translatedFormat('l, d F Y') }}</p>
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
