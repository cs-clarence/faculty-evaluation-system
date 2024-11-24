<?php

namespace App\Livewire\Pages\Admin\Courses;

use App\Livewire\Forms\CourseForm;
use App\Models\Course;
use App\Models\Department;
use Livewire\Component;

class Index extends Component
{
    public CourseForm $form;
    public ?Course $course;
    public bool $isFormOpen = false;

    public function render()
    {
        $courses = Course::withCount(['sections'])
            ->with(['department'])
            ->orderBy('code')
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at')
            ->get();

        $departments = Department::withoutArchived()
            ->orderBy('code')
            ->orderBy('name')
            ->lazy();

        return view('livewire.pages.admin.courses.index')
            ->with(compact('courses', 'departments'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->course = null;
        $this->form->clear();
    }

    public function edit(Course $course)
    {
        $this->isFormOpen = true;
        $this->form->set($course);
        $this->course = $course;
    }

    public function save()
    {
        $this->form->save();
        $this->closeForm();
    }

    public function delete(Course $course)
    {
        $course->delete();
    }

    public function unarchive(Course $course)
    {
        $course->unarchive();
    }

    public function archive(Course $course)
    {
        $course->archive();
    }
}
