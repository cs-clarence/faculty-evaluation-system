@props(['el' => 'div'])

<{{ $el }} {{ $attributes->merge(['class' => 'bg-white p-6 rounded-lg w-96 flex flex-col gap-4']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
