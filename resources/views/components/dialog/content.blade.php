@props(['el' => 'div'])

<{{ $el }} {{ $attributes->merge(['class' => 'flex flex-col gap-4']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
