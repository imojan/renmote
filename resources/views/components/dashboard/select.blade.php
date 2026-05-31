<select {{ $attributes->merge([
    'class' => 'block h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-rn-text transition focus:border-rn-primary focus:outline-none focus:ring-2 focus:ring-rn-primary/15',
]) }}>
    {{ $slot }}
</select>
