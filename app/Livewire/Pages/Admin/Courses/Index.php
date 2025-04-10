<?php
namespace App\Livewire\Pages\Admin\Courses;

use App\Livewire\Forms\CourseForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;
    public CourseForm $form;
    public ?Course $course;
    public bool $isFormOpen = false;

    public function render()
    {
        Gate::authorize('viewAny', Course::class);

        $courses = Course::withCount(['sections', 'courseSemesters', 'courseSubjects'])
            ->with(['department'])
            ->orderBy('code')
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at');

        if ($this->shouldSearch()) {
            $courses = $courses->fullTextSearch([
                'columns'   => ['name', 'code'],
                'relations' => [
                    'department' => [
                        'columns' => ['name', 'code'],
                    ],
                ],
            ], $this->searchText);
        }

        $courses = $courses->cursorPaginate(15);

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
        $this->course     = null;
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
        $this->form->submit();
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
