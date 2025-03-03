@php
    $base = [
        'x-show' => 'isOpen(key)',
        'class' =>
            'transition-all duration-500 origin-top starting:h-0 starting:rotate-x-90 [interpolate-size:allow-keywords]',
        'x-bind:class' => json_encode(['rotate-x-0 h-max' => 'isOpen(key)']),
    ];
@endphp

<div {{ $attributes->merge($base) }}>
    {{ $slot }}
</div>
