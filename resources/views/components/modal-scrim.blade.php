@props(['el' => 'div'])

<{{ $el }} {{ $attributes->merge(['class' => 'fixed inset-0 bg-gray-900/50']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
