<?php
namespace App\Livewire\Pages\Admin\Students;

use App\Livewire\Forms\UserForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Course;
use App\Models\RoleCode;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithSearch, WithPagination, WithoutUrlPagination;
    public bool $isFormOpen = false;
    public ?User $model;
    public UserForm $form;

    public function render()
    {
        $students = User::roleStudent()
            ->with([
                'student' => fn(HasOne $student) =>
                $student->with(['studentSubjects', 'studentSemesters', 'course'])
                    ->withCount(['studentSubjects', 'studentSemesters']),
            ])
            ->has('student');

        if ($this->shouldSearch()) {
            $students = $students->fullTextSearch([
                'columns'   => ['name', 'email'],
                'relations' => [
                    'student' => [
                        'columns'   => ['student_number'],
                        'relations' => [
                            'course' => [
                                'columns' => ['name', 'code'],
                            ],
                        ],
                    ],
                ],
            ], $this->searchText);
        }

        $students = $students->cursorPaginate(15);

        $courses = Course::withoutArchived()
            ->has('courseSubjects')
            ->orderBy('department_id')
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        $schoolYears = SchoolYear::active()
            ->orderByDesc('year_start')
            ->orderByDesc('year_end')
            ->lazy();

        return view('livewire.pages.admin.students.index')
            ->with(compact('students', 'courses', 'schoolYears'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->form->role_code = RoleCode::Student->value;
        $this->isFormOpen      = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->model      = null;
        $this->form->clear();
    }

    public function save()
    {
        $this->form->submit();
        $this->closeForm();
    }

    public function edit(User $model)
    {
        $model->load(['student', 'teacher']);
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
