@props([
    'type' => 'text',
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'placeholder' => ''
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500' . ($errors->has($name) ? ' border-red-500' : '')]) }}
    >
    
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
