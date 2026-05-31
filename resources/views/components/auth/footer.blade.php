@props([
    'text' => null,
])

{{--
    Auth footer link block. Use:
        <x-auth.footer text="Sudah punya akun?">
            <a href="...">Masuk</a>
        </x-auth.footer>
--}}
<div {{ $attributes->merge(['class' => 'mt-8 border-t border-gray-200 pt-6 text-center']) }}>
    @if($text)
        <p class="mb-1 text-sm text-gray-500">{{ $text }}</p>
    @endif
    <div class="text-[0.9375rem] font-bold text-rn-text underline underline-offset-[3px] transition-colors duration-150 hover:text-rn-primary [&_a]:transition-colors [&_a:hover]:text-rn-primary">
        {{ $slot }}
    </div>
</div>
