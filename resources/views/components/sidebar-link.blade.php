@props(['active' => false])

<a {{ $attributes->merge([
    'class' => 'flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors ' . 
               ($active ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '')
]) }}>
    {{ $slot }}
</a>
