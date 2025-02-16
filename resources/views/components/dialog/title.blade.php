@props(['el' => 'h3'])

<{{ $el }} {{ $attributes->merge(['class' => 'text-lg font-semibold']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
