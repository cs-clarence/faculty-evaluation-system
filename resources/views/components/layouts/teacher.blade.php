@props(['title' => 'SPCF-TES Teacher'])

<x-layouts.navigation :title="$title" :links="[
    [
        'href' => route('teacher.dashboard.index'),
        'title' => 'Dashboard',
    ],
]">
    {{ $slot }}
</x-layouts.navigation>
