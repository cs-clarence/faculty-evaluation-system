@props(['el' => 'div'])

<{{ $el }}
    {{ $attributes->merge(['class' => 'bg-white p-6 rounded-lg w-96 flex flex-col gap-4 transition starting:opacity-0 starting:-translate-y-[40px] translate-y-0 duration-400']) }}>
    @isset($slot)
        {{ $slot }}
    @endisset
    </{{ $el }}>
