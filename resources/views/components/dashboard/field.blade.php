@props([
    'label' => null,
    'for' => null,
    'error' => null,
    'hint' => null,
])

<label @if($for) for="{{ $for }}" @endif class="block">
    @if($label)
        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</span>
    @endif

    {{ $slot }}

    @if($hint)
        <span class="mt-1 block text-xs text-slate-400">{{ $hint }}</span>
    @endif

    @if($error)
        <span class="mt-1 block text-xs font-medium text-red-500">{{ $error }}</span>
    @endif
</label>
