@props(['el' => 'div'])

<{{ $el }}
    {{ $attributes->merge(['class' => 'bg-transparent flex flex-col items-center justify-center fixed inset-0 z-50']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
