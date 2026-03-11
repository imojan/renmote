@props(['active' => false])

<a {{ $attributes->merge([
    'class' => 'dash-nav-item ' . ($active ? 'active' : '')
]) }}>
    {{ $slot }}
</a>
