<?php
namespace App\Livewire\Pages\Admin\Teachers;

use App\Livewire\Forms\TeacherSemesterForm;
use App\Livewire\Forms\TeacherSubjectForm;
use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Teacher as Model;
use App\Models\TeacherSemester;
use App\Models\TeacherSubject;
use Livewire\Component;

class Teacher extends Component
{
    public Model $teacher;
    public TeacherSemesterForm $semesterForm;
    public ?TeacherSemester $semester;
    public bool $semesterFormIsOpen = false;
    public TeacherSubjectForm $subjectForm;
    public ?TeacherSubject $subject;
    public bool $subjectFormIsOpen = false;

    public function render()
    {
        $courses = Course::whereDepartmentId($this->teacher->department_id)
            ->has('courseSubjects');

        $sem = null;
        if (isset($this->semesterForm->semester_id)) {
            $sem = Semester::whereId($this->semesterForm->semester_id)
                ->first(['semester'])?->semester;
            $courses = $courses->whereHas('courseSemesters',
                function ($query) use ($sem) {
                    return $query->whereSemester($sem);
                });
        }

        $courses = $courses->lazy();

        $courseSubjects = [];

        if (isset($this->semesterForm->course_ids)) {
            $courseSubjects = CourseSubject::whereHas('courseSemester', function ($query) {
                return $query->whereHas('course',
                    fn($query) =>
                    $query->whereIn('id', $this->semesterForm->course_ids)
                );
            });

            if (isset($this->semester)) {
                $sem                      = $this->semester->semester->semester;
                $existingCourseSubjectIds = $this->semester
                    ->teacherSubjects()
                    ->get(['course_subject_id'])
                    ->pluck('course_subject_id')
                    ->toArray();

                $courseSubjects = $courseSubjects->whereNotIn('id', $existingCourseSubjectIds);
            }

            if (isset($sem)) {
                $courseSubjects = $courseSubjects->whereHas('courseSemester', fn($query) => $query->whereSemester($sem));
            }

            $courseSubjects = $courseSubjects->lazy();
        }

        $existingSemesterIds = $this->teacher->teacherSemesters->pluck('semester_id')->toArray();

        $sections = [];

        if (isset($this->subject)) {
            $sections = Section::whereCourseId($this->subject->courseSubject->course->id)
                ->whereSemester($this->subject->courseSubject->courseSemester->semester)
                ->lazy();
        }

        return view('livewire.pages.admin.teachers.teacher', [
            'courses'        => $courses,
            'courseSubjects' => $courseSubjects,
            'sections'       => $sections,
            'semesters'      => Semester::whereNotIn('id', $existingSemesterIds)
                ->orderByDesc('school_year_id')
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
        $this->semesterFormIsOpen = false;
        $this->semester           = null;
        $this->semesterForm->clear();
    }

    public function saveSemester()
    {
        if (! isset($this->semesterForm->teacher_id)) {
            $this->semesterForm->teacher_id = $this->teacher->id;
        }
        $this->semesterForm->submit();
        $this->closeSemesterForm();
    }

    public function openAddSubjectsForm(TeacherSemester $model)
    {
        $this->semesterForm->set($model);
        $this->semester                         = $model;
        $this->semesterForm->course_ids         = [];
        $this->semesterForm->course_subject_ids = [];
        $this->openSemesterForm();
        $this->semesterForm->include_base = false;
    }

    public function editSubject(TeacherSubject $model)
    {
        $this->subjectForm->set($model);
        $this->subject           = $model;
        $this->subjectFormIsOpen = true;
    }

    public function closeSubjectForm()
    {
        $this->subjectFormIsOpen = false;
        $this->subject           = null;
        $this->subjectForm->reset();
    }

    public function saveSubject()
    {
        $this->subjectForm->submit();
        $this->closeSubjectForm();
    }

    public function deleteSubject(TeacherSubject $model)
    {
        $model->delete();
    }

    public function archiveSubject(TeacherSubject $model)
    {
        $model->archive();
    }

    public function unarchiveSubject(TeacherSubject $model)
    {
        $model->unarchive();
    }

    public function deleteSemester(TeacherSemester $model)
    {
        $model->delete();
    }
}
