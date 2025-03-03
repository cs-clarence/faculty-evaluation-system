@props(['key' => random_int(1, 100)])

@php
    $data = json_encode(['key' => (string) $key]);
    $data = "accordionItem($data)";
@endphp

<div {{ $attributes->merge(['x-data' => $data, 'class' => '', 'wire:key' => $key, 'x-init' => 'init($dispatch)']) }}>
    {{ $slot }}
</div>
