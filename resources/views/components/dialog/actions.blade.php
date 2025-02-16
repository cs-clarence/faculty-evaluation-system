@props(['el' => 'div'])

<{{ $el }} {{ $attributes->merge(['class' => 'flex justify-end gap-1']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
