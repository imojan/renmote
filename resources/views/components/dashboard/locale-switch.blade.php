@php $current = app()->getLocale(); @endphp

<div {{ $attributes->merge(['class' => 'rn-dash-locale flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-500']) }}>
    <i class="fa fa-globe text-slate-400"></i>
    <a href="{{ route('locale.switch', 'en') }}"
       class="px-1.5 transition-colors {{ $current === 'en' ? 'rounded-full bg-rn-primary px-2 py-0.5 text-white' : 'hover:text-slate-800' }}">
        EN
    </a>
    <span class="text-slate-300">|</span>
    <a href="{{ route('locale.switch', 'id') }}"
       class="px-1.5 transition-colors {{ $current === 'id' ? 'rounded-full bg-rn-primary px-2 py-0.5 text-white' : 'hover:text-slate-800' }}">
        ID
    </a>
</div>
