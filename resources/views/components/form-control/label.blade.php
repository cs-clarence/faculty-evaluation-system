@props(['key' => '', 'el' => 'label', 'text'])

<x-label :$key :$el>{{ $text ?? $slot }}</x-label>
