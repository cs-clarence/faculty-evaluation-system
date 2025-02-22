<?php
namespace App\Livewire\Pages\Admin\Departments;

use App\Livewire\Forms\DepartmentForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;

    public DepartmentForm $form;
    public bool $isFormOpen = false;
    public ?Department $department;

    public function render()
    {
        $departments = Department::withCount(['courses'])
            ->orderBy('code')
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at');

        if ($this->shouldSearch()) {
            $departments = $departments->fullTextSearch([
                'columns' => ['name', 'code'],
            ], $this->searchText);
        }

        $departments = $departments->cursorPaginate(15);

        return view('livewire.pages.admin.departments.index')
            ->with(compact('departments'))
            ->layout('components.layouts.admin');
    }

    public function save()
    {
        $this->form->submit();
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
