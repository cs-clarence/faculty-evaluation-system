<?php

namespace App\Livewire\Pages\Admin\Teachers;

use App\Livewire\Forms\UserForm;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Component;

class Index extends Component
{

    public bool $isFormOpen = false;
    public ?Teacher $model;
    public UserForm $form;

    public function render()
    {
        $teachers = Teacher::with(['user' => function (BelongsTo $builder) {
            $builder->teacher();
        }])
            ->withCount(['teacherSubjects', 'teacherSemesters'])
            ->lazy();

        return view('livewire.pages.admin.teachers.index')
            ->with(compact('teachers'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    public function edit(Teacher $model)
    {
        $this->model = $model;
        $this->openForm();
    }

    public function delete(Teacher $model)
    {
        $model->delete();
    }

    public function archive(Teacher $model)
    {
        $model->archive();
    }

    public function unarchive(Teacher $model)
    {
        $model->unarchive();
    }
}
