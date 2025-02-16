@props(['flex' => 'column'])

<div {{ $attributes->merge(['class' => 'flex gap-1 ' . ($flex === 'row' ? 'flex-row' : 'flex-col')]) }}>
    {{ $slot }}
</div>
