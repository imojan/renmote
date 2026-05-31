{{--
    Auth divider with text in the middle.
    Usage: <x-auth.divider>atau</x-auth.divider>
--}}
<div {{ $attributes->merge(['class' => 'my-6 flex items-center gap-4']) }}>
    <span class="h-px flex-1 bg-gray-200"></span>
    <span class="text-[0.8125rem] font-medium lowercase text-gray-400">{{ $slot }}</span>
    <span class="h-px flex-1 bg-gray-200"></span>
</div>
