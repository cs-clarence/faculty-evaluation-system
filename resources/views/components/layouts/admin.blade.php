@props(['title' => 'SPCF-TES Admin'])

<x-layouts.navigation :title="$title" :links="[
    [
        'href' => route('admin.dashboard.index'),
        'title' => 'Dashboard',
    ],
    [
        'href' => route('admin.school-years.index'),
        'title' => 'School Years',
    ],
    [
        'href' => route('admin.subjects.index'),
        'title' => 'Subjects',
    ],
    [
        'href' => route('admin.departments.index'),
        'title' => 'Departments',
    ],
    [
        'href' => route('admin.courses.index'),
        'title' => 'Courses',
    ],
    [
        'href' => route('admin.sections.index'),
        'title' => 'Sections',
    ],
    [
        'href' => route('admin.teachers.index'),
        'title' => 'Teachers',
    ],
    [
        'href' => route('admin.students.index'),
        'title' => 'Students',
    ],
    [
        'href' => route('admin.forms.index'),
        'title' => 'Forms',
    ],
    [
        'href' => route('admin.form-submission-periods.index'),
        'title' => 'Form Submission Periods',
    ],
    [
        'href' => route('admin.accounts.index'),
        'title' => 'Accounts',
    ],
]">
    {{ $slot }}
</x-layouts.navigation>
