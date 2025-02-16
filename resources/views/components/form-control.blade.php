@props(['flex' => 'column'])

<div {{ $attributes->merge(['class' => 'flex ' . ($flex === 'row' ? 'flex-row' : 'flex-col')]) }}>
    {{ $slot }}
</div>
