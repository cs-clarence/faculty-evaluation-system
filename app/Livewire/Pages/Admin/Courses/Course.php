<?php

namespace App\Livewire\Pages\Admin\Courses;

use App\Livewire\Forms\CourseSemesterForm;
use App\Models\Course as CourseModel;
use App\Models\CourseSemester;
use App\Models\Subject;
use Livewire\Component;

class Course extends Component
{
    public CourseModel $course;
    public bool $isCourseSemesterFormOpen = false;
    public ?CourseSemester $courseSemester;
    public ?int $openCourseSemesterId = null;
    public CourseSemesterForm $courseSemesterForm;

    public function render()
    {
        $subjects = Subject::query()->lazy();

        return view('livewire.pages.admin.courses.course')
            ->with([
                'hcourse' => $this->course,
                'subjects' => $subjects,
                'courseSemesters' => $this->course->courseSemesters()
                    ->withCount(['subjects'])
                    ->orderBy('year_level')
                    ->orderBy('semester')
                    ->lazy(),
            ])

            ->layout('components.layouts.admin');
    }

    public function openCourseSemesterForm()
    {
        $this->isCourseSemesterFormOpen = true;
        $this->courseSemesterForm->course_id = $this->course->id;
    }

    public function closeCourseSemesterForm()
    {
        $this->isCourseSemesterFormOpen = false;
        $this->courseSemester = null;
        $this->courseSemesterForm->clear();
    }

    public function editCourseSemester(CourseSemester $courseSemester)
    {
        $this->courseSemester = $courseSemester;
        $this->courseSemesterForm->set($courseSemester);
        $this->isCourseSemesterFormOpen = true;
    }

    public function saveCourseSemester()
    {
        $this->courseSemesterForm->save();
        $this->closeCourseSemesterForm();
    }

    public function deleteCourseSemester(CourseSemester $courseSemester)
    {
        $courseSemester->delete();
    }

    public function isOpenAccordion(CourseSemester $courseSemester)
    {
        return $this->openCourseSemesterId === $courseSemester->id;
    }

    public function toggleAccordion(CourseSemester $courseSemester)
    {
        if ($this->isOpenAccordion($courseSemester)) {
            $this->openCourseSemesterId = null;
        } else {
            $this->openCourseSemesterId = $courseSemester->id;
        }
    }
}
