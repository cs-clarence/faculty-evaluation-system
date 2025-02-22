@props(['key' => null, 'value' => ''])

<div {{ $attributes->merge(['class' => 'flex flex-row gap']) }} x-data="{ search: '' }" x-modelable="search">
    <x-input placeholder="Search" type="text"
        class="rounded-b-full rounded-t-full rounded-r-full rounded-l-full min-w-[300px]" x-model="search"
        value="{{ $value }}" x-on:keyup.debounce.500ms="$dispatch('input', search)"
        x-on:keyup.enter.debounce.500ms="$dispatch('input', search)" />

    <x-button icon variant="text" x-on:click="$dispatch('input', search)">
        <x-icon>search</x-icon>
    </x-button>
</div>
