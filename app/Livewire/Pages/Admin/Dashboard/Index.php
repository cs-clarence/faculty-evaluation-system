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
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $statistics = [
            [
                'label' => 'Departments',
                'value' => Department::count(),
                'href' => route('admin.departments.index'),
            ],
            [
                'label' => 'Courses',
                'value' => Course::count(),
                'href' => route('admin.courses.index'),
            ],
            [

                'label' => 'Subjects',
                'value' => Subject::count(),
                'href' => route('admin.subjects.index'),
            ],
            [
                'label' => 'Students',
                'value' => Student::count(),
                'href' => route('admin.students.index'),
            ],
            [
                'label' => 'Teachers',
                'value' => Teacher::count(),
                'href' => route('admin.teachers.index'),
            ],
            [
                'label' => 'Accounts',
                'value' => User::count(),
                'href' => route('admin.accounts.index'),
            ],
            [
                'label' => 'Forms',
                'value' => Form::count(),
                'href' => route('admin.forms.index'),
            ],
            [
                'label' => 'Form Submission Periods',
                'value' => FormSubmissionPeriod::count(),
                'href' => route('admin.form-submission-periods.index'),
            ],
            [
                'label' => 'Form Submissions',
                'value' => FormSubmission::count(),
                'href' => route('admin.form-submissions.index'),
            ],
            [
                'label' => 'School Years',
                'value' => SchoolYear::count(),
                'href' => route('admin.school-years.index'),
            ],
            [
                'label' => 'Sections',
                'value' => Section::count(),
                'href' => route('admin.sections.index'),
            ],
        ];

        return view('livewire.pages.admin.dashboard.index')
            ->with(compact('statistics'))
            ->layout('components.layouts.admin');
    }
}
