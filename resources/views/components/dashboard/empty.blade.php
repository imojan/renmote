@props([
    'icon' => 'fa-inbox',
    'message' => null,
])

<div class="flex flex-col items-center gap-2 px-6 py-10 text-center text-sm text-slate-500">
    <i class="fa-solid {{ $icon }} text-3xl text-slate-300"></i>
    @if($message)
        <span>{{ $message }}</span>
    @else
        {{ $slot }}
    @endif
</div>
