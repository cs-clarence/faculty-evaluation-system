@props(['key' => null])

<textarea
    {{ $attributes->merge(['class' => 'w-full px-3 py-2 border-2 inset-ring-1 inset-ring-gray-400 border-blue-500/0 focus:border-blue-500 focus:inset-ring-0 focus:ring-0 rounded-lg disabled:opacity-50', 'id' => $key, 'name' => $key, 'x-on:clear-inputs.window' => "\$el.value = ''"]) }}>
{{ $slot }}
</textarea>
