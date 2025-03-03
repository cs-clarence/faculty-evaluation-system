<?php
namespace App\Livewire\Pages\Admin\Teachers;

use App\Livewire\Forms\TeacherSemesterForm;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Teacher as Model;
use App\Models\TeacherSemester;
use App\Models\TeacherSubject;
use Livewire\Component;

class Teacher extends Component
{
    public Model $teacher;
    public TeacherSemesterForm $semesterForm;
    public bool $semesterFormIsOpen = false;

    public function render()
    {
        $courses = Course::whereDepartmentId($this->teacher->department_id)
            ->has('courseSubjects')
            ->lazy();

        $courseSubjects = [];

        if (isset($this->semesterForm->course_id)) {
        }

        return view('livewire.pages.admin.teachers.teacher', [
            'courses'        => $courses,
            'courseSubjects' => $courseSubjects,
            'semesters'      => Semester::orderByDesc('school_year_id')
                ->orderByDesc('semester')
                ->lazy(),
        ])
            ->layout('components.layouts.admin');
    }

    public function editSemester(TeacherSemester $model)
    {
        $this->semesterForm->set($model);
        $this->openSemesterForm();
    }

    public function openSemesterForm()
    {
        $this->semesterFormIsOpen             = true;
        $this->semesterForm->include_base     = true;
        $this->semesterForm->include_subjects = true;
    }
    public function closeSemesterForm()
    {
        $this->semesterForm->clear();
        $this->semesterFormIsOpen = false;
    }

    public function saveSemester()
    {
        $this->semesterForm->submit();
        $this->closeSemesterForm();
    }

    public function openSubjectForm(TeacherSubject $model)
    {}

    public function openAddSubjectsForm(TeacherSemester $model)
    {
        $this->semesterForm->set($model);
        $this->openSemesterForm();
        $this->semesterForm->include_base = false;
    }
}
