<?php

namespace App\Livewire\Pages\Admin\Students;

use App\Livewire\Forms\UserForm;
use App\Models\Course;
use App\Models\RoleCode;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Component;

class Index extends Component
{
    public bool $isFormOpen = false;
    public ?User $model;
    public UserForm $form;

    public function render()
    {
        $students = Student::with([
            'user' => fn(BelongsTo $user) => $user->student(),
            'course' => fn(BelongsTo $course) => $course->first(['name', 'code']),
            'schoolYear' => fn(BelongsTo $schoolYear) => $schoolYear->first(['year_start', 'year_end']),
        ])
            ->withCount(['studentSubjects', 'studentSemesters'])
            ->orderBy('student_number')
            ->lazy();

        $courses = Course::withoutArchived()
            ->orderBy('department_id')
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        $schoolYears = SchoolYear::orderByDesc('year_start')
            ->orderByDesc('year_end')
            ->lazy();

        return view('livewire.pages.admin.students.index')
            ->with(compact('students', 'courses', 'schoolYears'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->form->role_code = RoleCode::Student->value;
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->model = null;
        $this->form->clear();
    }

    public function save()
    {
        $this->form->submit();
        $this->closeForm();
    }

    public function edit(User $model)
    {
        $this->model = $model;
        $this->form->set($model);
        $this->form->setupEdit(includeBase: true);
        $this->openForm();
    }

    public function editPassword(User $model)
    {
        $this->model = $model;
        $this->form->set($model);
        $this->form->setupEdit(includePassword: true);
        $this->openForm();
    }

    public function delete(User $model)
    {
        $model->delete();
    }

    public function archive(User $model)
    {
        $model->archive();
    }

    public function unarchive(User $model)
    {
        $model->unarchive();
    }
}
