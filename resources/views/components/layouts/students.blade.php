@props(['title' => 'SPCF-TES Student'])

<x-layouts.navigation :title="$title" :links="[
    [
        'href' => route('student.dashboard.index'),
        'title' => 'Dashboard',
    ],
]">
    {{ $slot }}
</x-layouts.navigation>
