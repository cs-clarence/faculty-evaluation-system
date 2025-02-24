@props(['title' => 'SPCF-TES'])

<x-layouts.navigation :title="$title" :links="[
    [
        'href' => route('user.dashboard.index'),
        'title' => 'Dashboard',
    ],
    [
        'href' => route('user.pending-evaluations.index'),
        'title' => 'Pending Evaluations',
        'condition' => auth()->user()->role->can_be_evaluator,
    ],
    [
        'href' => route('user.received-evaluations.index'),
        'title' => 'Evaluations Received',
        'condition' => auth()->user()->role->can_be_evaluatee,
    ],
    [
        'href' => route('user.submitted-evaluations.index'),
        'title' => 'Evaluations Submitted',
        'condition' => auth()->user()->role->can_be_evaluator,
    ],
    [
        'href' => route('user.faculty-evaluations.index'),
        'title' => 'Faculty Evaluations',
        'condition' => auth()->user()->isDean(),
    ],
]">
    {{ $slot }}
</x-layouts.navigation>
