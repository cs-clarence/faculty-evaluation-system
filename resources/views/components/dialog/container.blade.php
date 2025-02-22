@props(['el' => 'div'])

<{{ $el }}
    {{ $attributes->merge(['class' => 'bg-transparent flex flex-col items-center [justify-content:safe_center] fixed inset-0 z-50 overflow-y-auto']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
