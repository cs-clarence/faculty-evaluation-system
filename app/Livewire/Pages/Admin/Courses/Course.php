<?php
namespace App\Livewire\Pages\Admin\Courses;

use App\Livewire\Forms\AddCourseSubjectsForm;
use App\Livewire\Forms\CourseSemesterForm;
use App\Models\Course as CourseModel;
use App\Models\CourseSemester;
use App\Models\CourseSubject;
use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Course extends Component
{
    public CourseModel $course;
    public bool $isCourseSemesterFormOpen    = false;
    public bool $isAddCourseSubjectsFormOpen = false;
    public ?CourseSemester $courseSemester;
    public ?int $openCourseSemesterId = null;
    public CourseSemesterForm $courseSemesterForm;
    public AddCourseSubjectsForm $addCourseSubjectsForm;

    public function render()
    {
        Gate::authorize('view', $this->course);

        $subjectQuery = Subject::withoutArchived()
            ->orderBy('code')
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at');

        $subjects = $subjectQuery->lazy();

        $filteredSubjects = isset($this->addCourseSubjectsForm->course_semester_id)
        ? $subjectQuery
            ->whereNotIn('id', CourseSubject::whereCourseSemesterId($this->addCourseSubjectsForm->course_semester_id)->get(['subject_id'])->pluck('subject_id')->toArray())
            ->lazy()
        : [];

        return view('livewire.pages.admin.courses.course')
            ->with([
                'hcourse'          => $this->course,
                'subjects'         => $subjects,
                'courseSemesters'  => $this->course->courseSemesters()
                    ->withCount(['subjects'])
                    ->orderBy('year_level')
                    ->orderBy('semester')
                    ->lazy(),
                'filteredSubjects' => $filteredSubjects,
            ])

            ->layout('components.layouts.admin');
    }

    public function openCourseSemesterForm()
    {
        $this->isCourseSemesterFormOpen      = true;
        $this->courseSemesterForm->course_id = $this->course->id;
    }

    public function closeCourseSemesterForm()
    {
        $this->isCourseSemesterFormOpen = false;
        $this->courseSemester           = null;
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
        $this->courseSemesterForm->submit();
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

    public function deleteCourseSubject(CourseSubject $courseSubject)
    {
        $courseSubject->delete();
    }

    public function archiveCourseSubject(CourseSubject $courseSubject)
    {
        $courseSubject->archive();
    }

    public function unarchiveCourseSubject(CourseSubject $courseSubject)
    {
        $courseSubject->unarchive();
    }

    public function toggleAccordion(CourseSemester $courseSemester)
    {
        if ($this->isOpenAccordion($courseSemester)) {
            $this->openCourseSemesterId = null;
        } else {
            $this->openCourseSemesterId = $courseSemester->id;
        }
    }
    public function openAddCourseSubjectsForm(CourseSemester $courseSemester)
    {
        $this->isAddCourseSubjectsFormOpen = true;
        $this->addCourseSubjectsForm->set($courseSemester);
    }

    public function saveAddCourseSubjects()
    {
        $this->addCourseSubjectsForm->save();
        $this->closeAddCourseSubjectsForm();
    }

    public function closeAddCourseSubjectsForm()
    {
        $this->isAddCourseSubjectsFormOpen = false;
        $this->addCourseSubjectsForm->clear();
    }
}
