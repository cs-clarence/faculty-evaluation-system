@php
    use Illuminate\Support\Facades\Gate;
    use App\Models\{
        SchoolYear,
        Subject,
        Department,
        Course,
        Section,
        Teacher,
        Student,
        Form,
        FormSubmissionPeriod,
        FormSubmission,
        User,
        Dean,
    };
@endphp

@props(['title' => 'SPCF-TES Admin'])



<x-layouts.navigation :title="$title" :links="[
    [
        'href' => route('admin.dashboard.index'),
        'title' => 'Dashboard',
    ],
    [
        'href' => route('admin.pending-evaluations.index'),
        'title' => 'Pending Evaluations',
        'condition' => auth()->user()->role->can_be_evaluator,
    ],
    [
        'href' => route('admin.submitted-evaluations.index'),
        'title' => 'Evaluations Submitted',
        'condition' => auth()->user()->role->can_be_evaluator,
    ],
    [
        'href' => route('evaluation-summaries'),
        'title' => 'Evaluation Summaries',
        'condition' => Gate::allows('viewSummaries', FormSubmission::class),
    ],
    [
        'href' => route('admin.school-years.index'),
        'title' => 'School Years',
        'condition' => Gate::allows('viewAny', SchoolYear::class),
    ],
    [
        'href' => route('admin.subjects.index'),
        'title' => 'Subjects',
        'condition' => Gate::allows('viewAny', Subject::class),
    ],
    [
        'href' => route('admin.departments.index'),
        'title' => 'Departments',
        'condition' => Gate::allows('viewAny', Department::class),
    ],
    [
        'href' => route('admin.courses.index'),
        'title' => 'Courses',
        'condition' => Gate::allows('viewAny', Course::class),
    ],
    [
        'href' => route('admin.sections.index'),
        'title' => 'Sections',
        'condition' => Gate::allows('viewAny', Section::class),
    ],
    [
        'href' => route('admin.deans.index'),
        'title' => 'Deans',
        'condition' => Gate::allows('viewAny', Dean::class),
    ],
    [
        'href' => route('admin.teachers.index'),
        'title' => 'Teachers',
        'condition' => Gate::allows('viewAny', Teacher::class),
    ],
    [
        'href' => route('admin.students.index'),
        'title' => 'Students',
        'condition' => Gate::allows('viewAny', Student::class),
    ],
    [
        'href' => route('admin.forms.index'),
        'title' => 'Forms',
        'condition' => Gate::allows('viewAny', Form::class),
    ],
    [
        'href' => route('admin.form-submission-periods.index'),
        'title' => 'Form Submission Periods',
        'condition' => Gate::allows('viewAny', FormSubmissionPeriod::class),
    ],
    [
        'href' => route('admin.form-submissions.index'),
        'title' => 'Form Submissions',
        'condition' => Gate::allows('viewAny', FormSubmission::class),
    ],
    [
        'href' => route('admin.accounts.index'),
        'title' => 'Accounts',
        'condition' => Gate::allows('viewAny', User::class),
    ],
]">
    {{ $slot }}
</x-layouts.navigation>
