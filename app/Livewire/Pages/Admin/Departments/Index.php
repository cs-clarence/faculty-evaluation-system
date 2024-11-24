<?php

namespace App\Livewire\Pages\Admin\Departments;

use App\Livewire\Forms\DepartmentForm;
use App\Models\Department;
use Livewire\Component;

class Index extends Component
{
    public DepartmentForm $form;
    public bool $isFormOpen = false;
    public ?Department $department;

    public function render()
    {
        $departments = Department::withCount(['courses'])
            ->orderBy('code')
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at')
            ->lazy();

        return view('livewire.pages.admin.departments.index')
            ->with(compact('departments'))
            ->layout('components.layouts.admin');
    }

    public function save()
    {
        $this->form->save();
        $this->closeForm();
    }

    public function openForm()
    {
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->form->clear();
        $this->department = null;
    }

    public function delete(Department $department)
    {
        $department->delete();
    }

    public function edit(Department $department)
    {
        $this->department = $department;
        $this->isFormOpen = true;
        $this->form->set($department);
    }

    public function archive(Department $department)
    {
        $department->archive();
    }

    public function unarchive(Department $department)
    {
        $department->unarchive();
    }
}
