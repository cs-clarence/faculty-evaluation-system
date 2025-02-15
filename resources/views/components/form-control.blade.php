@props(['flex' => 'column'])

<div {{ $attributes->merge(['class' => 'mb-4 flex ' . ($flex === 'row' ? 'flex-row' : 'flex-col')]) }}>
    {{ $slot }}
</div>
