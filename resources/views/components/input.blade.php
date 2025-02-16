@props(['key' => null])

<input {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-lg', 'id' => $key, 'name' => $key]) }}>
