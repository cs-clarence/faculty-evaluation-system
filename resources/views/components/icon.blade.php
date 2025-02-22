@props(['name', 'variant' => 'outlined'])

@php
    $class = match (str($variant)->lower()->toString()) {
        'outlined' => 'material-symbols-outlined',
        'rounded' => 'material-symbols-rounded',
        'sharp' => 'material-symbols-sharp',
    };
@endphp

<span class="{{ $class }}">
    {{ $name ?? $slot }}
</span>
