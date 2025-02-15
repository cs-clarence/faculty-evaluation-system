<div class="content">
    @if (auth()->user()->isStudent())
        <livewire:components.student.dashboard-content />
    @elseif (auth()->user()->isTeacher())
        <livewire:components.teacher.dashboard-content />
    @elseif (auth()->user()->isDean())
        <livewire:components.dean.dashboard-content />
    @endif
</div>
