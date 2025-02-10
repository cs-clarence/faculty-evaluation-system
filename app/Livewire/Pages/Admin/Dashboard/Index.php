<?php
namespace App\Livewire\Pages\Admin\Dashboard;

use App\Models\Course;
use App\Models\Department;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\FormSubmissionPeriod;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $statistics = [
            [
                'label'     => 'Departments',
                'value'     => Department::count(),
                'href'      => route('admin.departments.index'),
                'condition' => Gate::allows('viewAny', Department::class),
            ],
            [
                'label'     => 'Courses',
                'value'     => Course::count(),
                'href'      => route('admin.courses.index'),
                'condition' => Gate::allows('viewAny', Course::class),
            ],
            [

                'label'     => 'Subjects',
                'value'     => Subject::count(),
                'href'      => route('admin.subjects.index'),
                'condition' => Gate::allows('viewAny', Subject::class),
            ],
            [
                'label'     => 'Students',
                'value'     => Student::count(),
                'href'      => route('admin.students.index'),
                'condition' => Gate::allows('viewAny', Student::class),
            ],
            [
                'label'     => 'Teachers',
                'value'     => Teacher::count(),
                'href'      => route('admin.teachers.index'),
                'condition' => Gate::allows('viewAny', Teacher::class),
            ],
            [
                'label'     => 'Accounts',
                'value'     => User::count(),
                'href'      => route('admin.accounts.index'),
                'condition' => Gate::allows('viewAny', User::class),
            ],
            [
                'label'     => 'Forms',
                'value'     => Form::count(),
                'href'      => route('admin.forms.index'),
                'condition' => Gate::allows('viewAny', Form::class),
            ],
            [
                'label'     => 'Form Submission Periods',
                'value'     => FormSubmissionPeriod::count(),
                'href'      => route('admin.form-submission-periods.index'),
                'condition' => Gate::allows('viewAny', FormSubmissionPeriod::class),
            ],
            [
                'label'     => 'Form Submissions',
                'value'     => FormSubmission::count(),
                'href'      => route('admin.form-submissions.index'),
                'condition' => Gate::allows('viewAny', FormSubmission::class),
            ],
            [
                'label'     => 'School Years',
                'value'     => SchoolYear::count(),
                'href'      => route('admin.school-years.index'),
                'condition' => Gate::allows('viewAny', SchoolYear::class),
            ],
            [
                'label'     => 'Sections',
                'value'     => Section::count(),
                'href'      => route('admin.sections.index'),
                'condition' => Gate::allows('viewAny', Section::class),
            ],
        ];

        return view('livewire.pages.admin.dashboard.index')
            ->with(compact('statistics'))
            ->layout('components.layouts.admin');
    }
}
