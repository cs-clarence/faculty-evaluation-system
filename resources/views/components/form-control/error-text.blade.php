@props(['key', 'el' => 'span'])

@isset($key)
    @error($key)
        <x-error-text :el="$el">
            {{ $message }}
        </x-error-text>
    @enderror
@endisset
