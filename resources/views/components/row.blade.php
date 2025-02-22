@props(['el' => 'div'])

<{{ $el }} {{ $attributes->merge(['class' => 'flex flex-row gap-1']) }}>
    {{ $slot }}
    </{{ $el }}>
