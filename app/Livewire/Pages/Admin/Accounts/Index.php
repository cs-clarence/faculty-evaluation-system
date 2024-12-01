<?php

namespace App\Livewire\Pages\Admin\Accounts;

use App\Livewire\Forms\UserForm;
use App\Models\Course;
use App\Models\Department;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public bool $isFormOpen = false;
    public ?User $model = null;
    public UserForm $form;

    public function render()
    {
        $users = User::with(['role'])
            ->orderBy('name')
            ->orderBy('email')
            ->lazy();

        $courseId = $this->model?->student?->course_id;
        $departmentId = $this->model?->teacher?->department_id;

        $courses = Course::withoutArchived(isset($courseId) ? [$courseId] : [])
            ->orderBy('department_id')
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        $departments = Department::withoutArchived(isset($departmentId) ? [$departmentId] : [])
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        $roles = Role::orderBy('code')->orderBy('display_name')->lazy();

        $schoolYears = SchoolYear::active()
            ->orderByDesc('year_start')
            ->orderByDesc('year_end')
            ->lazy();

        return view('livewire.pages.admin.accounts.index')
            ->with(compact('users', 'courses', 'departments', 'roles', 'schoolYears'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        if (!isset($this->form->role_code)) {
            $firstRoleCode = Role::whereCode('admin')->first(['code'])->code;
            $this->form->role_code = $firstRoleCode;
        }
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
