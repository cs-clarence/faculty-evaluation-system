@props(['title' => 'SPCF-TES'])

<div class="top flex col justify-end mb-4 items-end">
    <h1 class="text-2xl font-bold">{{ $title }}</h1>
    <div class="grow"></div>
    @isset($slot)
        {{ $slot }}
    @endisset
</div>
