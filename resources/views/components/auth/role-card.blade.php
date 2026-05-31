@props([
    'name' => 'role',
    'value',
    'title',
    'description' => null,
    'checked' => false,
])

{{--
    Role picker card used on the register page.
    Active state is driven by the radio input ":has(:checked)" selector,
    so we no longer need JS to toggle classes.
--}}
<label
    {{ $attributes->merge([
        'class' => 'group relative flex cursor-pointer flex-col items-center justify-center gap-1.5 rounded-xl border-[1.5px] border-gray-300 bg-white px-3 py-[18px] text-center transition-all duration-200 hover:border-gray-400 hover:bg-gray-50 has-[:checked]:border-rn-primary has-[:checked]:bg-rn-primary/5 has-[:checked]:shadow-[0_0_0_3px_rgba(21,101,192,0.12)]',
    ]) }}
>
    <input
        type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked($checked)
        class="pointer-events-none absolute opacity-0"
    >
    <span class="role-icon h-8 w-8 text-gray-400 transition-colors group-has-[:checked]:text-rn-primary">
        {{ $slot }}
    </span>
    <span class="text-sm font-bold text-gray-700 transition-colors group-has-[:checked]:text-rn-primary">{{ $title }}</span>
    @if($description)
        <span class="text-xs leading-tight text-gray-400">{{ $description }}</span>
    @endif
</label>
