<?php

namespace App\Livewire\Pages\Admin\Teachers;

use App\Livewire\Forms\UserForm;
use App\Models\Department;
use App\Models\RoleCode;
use App\Models\Teacher;
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
        $teachers = Teacher::with([
            'user' => fn(BelongsTo $user) => $user->teacher(),
            'department' => fn(BelongsTo $department) => $department->first(['code', 'name']),
        ])
            ->withCount(['teacherSubjects', 'teacherSemesters'])
            ->lazy();
        $exceptDepartmentIds = isset($this->model->department_id) ? [$this->model->department_id] : [];

        $departments = Department::withoutArchived($exceptDepartmentIds)
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        return view('livewire.pages.admin.teachers.index')
            ->with(compact('teachers', 'departments'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->form->role_code = RoleCode::Teacher->value;
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
