@props(['el' => 'div'])

<{{ $el }} {{ $attributes->merge(['class' => 'flex flex-col gap-1']) }}>
    {{ $slot }}
    </{{ $el }}>
