@props(['el' => 'span'])
<{{ $el }} {{ $attributes->merge(['class' => 'text-red-500 text-sm']) }}>
    {{ $slot }}
    </{{ $el }}>
