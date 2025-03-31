<?php
namespace App\Livewire\Pages\Admin\Deans;

use App\Livewire\Forms\UserForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Department;
use App\Models\RoleCode;
use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;

    public bool $isFormOpen = false;
    public ?User $model;
    public UserForm $form;

    public function render()
    {
        $deans = User::roleDean()->with(['dean.department']);

        if ($this->shouldSearch()) {
            $deans = $deans->fullTextSearch([
                'deans'     => ['name', 'email'],
                'relations' => [
                    'dean' => [
                        'relations' => [
                            'department' => [
                                'columns' => ['name', 'code'],
                            ],
                        ],
                    ],
                ],
            ], $this->searchText);
        }

        $deans = $deans->cursorPaginate(15);

        $exceptDepartmentIds = isset($this->model->dean->department_id) ? [$this->model->dean->department_id] : [];

        $departments = Department::withoutArchived($exceptDepartmentIds)
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        return view('livewire.pages.admin.deans.index')
            ->layout('components.layouts.admin')
            ->with([
                'deans'       => $deans,
                'departments' => $departments,
            ]);
    }

    public function openForm()
    {
        $this->form->role_code = RoleCode::Dean->value;
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
}
