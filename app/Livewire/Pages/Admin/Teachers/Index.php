<?php
namespace App\Livewire\Pages\Admin\Teachers;

use App\Livewire\Forms\UserForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Department;
use App\Models\RoleCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;

    public bool $isFormOpen = false;
    public ?User $model;
    public UserForm $form;
    public ?int $filter_department_id = null;

    public function render()
    {
        $teachers = User::roleTeacher()
            ->with(['teacher' => fn(HasOne $teacher) => $teacher
                    ->withCount(['teacherSubjects', 'teacherSemesters'])
                    ->with(['department']),
            ]);

        if (isset($this->filter_department_id)) {
            $teachers = $teachers->whereHas(
                'teacher',
                fn($q) => $q->where('department_id', $this->filter_department_id)
            );
        } else {
            $teachers = $teachers->has('teacher');
        }

        if ($this->shouldSearch()) {
            $teachers = $teachers->fullTextSearch([
                'columns'   => ['name', 'email'],
                'relations' => [
                    'teacher' => [
                        'relations' => [
                            'department' => [
                                'columns' => ['name', 'code'],
                            ],
                        ],
                    ],
                ],
            ], $this->searchText);
        }

        $teachers = $teachers->cursorPaginate(15);

        $exceptDepartmentIds = isset($this->model->teacher->department_id) ? [$this->model->teacher->department_id] : [];

        $departments = Department::withoutArchived($exceptDepartmentIds)
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        $departmentFilters = $departments;

        return view('livewire.pages.admin.teachers.index')
            ->with(compact('teachers', 'departments', 'departmentFilters'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->form->role_code = RoleCode::Teacher->value;
        $this->isFormOpen      = true;
        $this->form->prefill();
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

    public function resetFilters()
    {
        $this->filter_department_id = null;
        $this->searchText           = null;
    }
}
