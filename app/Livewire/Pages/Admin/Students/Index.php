<?php

namespace App\Livewire\Pages\Admin\Students;

use App\Livewire\Forms\UserForm;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Component;

class Index extends Component
{
    public bool $isFormOpen = false;
    public ?Student $model;
    public UserForm $form;

    public function render()
    {
        $students = Student::with(['user' => function (BelongsTo $builder) {
            $builder->student();
        }])
            ->withCount(['studentSubjects', 'studentSemesters'])
            ->lazy();

        return view('livewire.pages.admin.students.index')
            ->with(compact('students'))
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

    public function edit(Student $model)
    {
        $this->model = $model;
        $this->openForm();
    }

    public function delete(Student $model)
    {
        $model->delete();
    }

    public function archive(Student $model)
    {
        $model->archive();
    }

    public function unarchive(Student $model)
    {
        $model->unarchive();
    }
}
