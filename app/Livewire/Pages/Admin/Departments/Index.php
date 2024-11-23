<?php

namespace App\Livewire\Pages\Admin\Departments;

use App\Livewire\Forms\DepartmentForm;
use App\Models\Department;
use Livewire\Component;

class Index extends Component
{
    public DepartmentForm $form;

    public ?Department $department = null;

    public function render()
    {
        $departments = Department::all();
        return view('livewire.pages.admin.departments.index')
            ->with(compact('departments'))
            ->layout('components.layouts.admin');
    }

    public function save()
    {
        $this->form->save();
    }

    public function openForm()
    {
        $this->form->open();
    }

    public function closeForm()
    {
        $this->form->close();
    }

    public function delete(Department $department)
    {
        $department->delete();
    }

    public function edit(Department $department)
    {
        $this->form->open($department);
        $this->department = $department;
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
