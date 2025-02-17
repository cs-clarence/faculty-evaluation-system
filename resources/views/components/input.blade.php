@props(['key' => null])

<input
    {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-lg disabled:opacity-50', 'id' => $key, 'name' => $key]) }}>
