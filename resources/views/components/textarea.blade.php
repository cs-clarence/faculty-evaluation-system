@props(['key' => null])

<textarea {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-lg', 'id' => $key, 'name' => $key]) }}>
{{ $slot }}
</textarea>
