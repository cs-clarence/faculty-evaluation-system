@props(['el' => 'label', 'key'])

<{{ $el }} {{ $attributes->merge(['class' => 'block text-gray-700', 'for' => $key]) }}>
    {{ $slot }}
    </{{ $el }}>
