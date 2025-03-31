@props(['key' => null])

@php
    $randomId = 'textarea_' . Str::random(10);
@endphp

<div x-data="{ textAreaLength: 0 }" class="relative {{ $attributes->get('class') }}" x-init="$nextTick(() => textAreaLength = $refs.{{ $randomId }}.value.length)">
    <textarea @if (!$attributes->has('id')) id="{{ $randomId }}" @endif x-ref="{{ $randomId }}"
        x-on:input="textAreaLength = $event.target.value.length"
        {{ $attributes->filter(fn($key) => $key !== 'class')->merge(['class' => 'relative w-full px-3 py-2 border-2 inset-ring-1 inset-ring-gray-400 border-blue-500/0 focus:border-blue-500 focus:inset-ring-0 focus:ring-0 rounded-lg disabled:opacity-50', 'id' => $key, 'name' => $key, 'x-on:clear-inputs.window' => "\$el.value = ''"]) }}>
{{ $slot }}
</textarea>

    @if ($attributes->has('maxlength'))
        <span class="absolute right-3 bottom-3 text-sm text-gray-500">
            <span x-text="textAreaLength"></span> / {{ $attributes->get('maxlength') }}
        </span>
    @endif
</div>
