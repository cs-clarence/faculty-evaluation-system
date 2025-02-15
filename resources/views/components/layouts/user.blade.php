@props(['title' => 'SPCF-TES'])

<x-layouts.navigation :title="$title" :links="[
    [
        'href' => route('user.dashboard.index'),
        'title' => 'Dashboard',
    ],
]">
    {{ $slot }}
</x-layouts.navigation>
