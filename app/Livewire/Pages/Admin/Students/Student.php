<?php
namespace App\Livewire\Pages\Admin\Students;

use App\Livewire\Forms\StudentSemesterForm;
use App\Livewire\Forms\StudentSubjectForm;
use App\Models\Course;
use App\Models\CourseSemester;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student as Model;
use App\Models\StudentSemester;
use App\Models\StudentSubject;
use Livewire\Component;

class Student extends Component
{
    public Model $student;

    public StudentSemesterForm $semesterForm;
    public ?StudentSemester $semester;
    public ?CourseSemester $courseSemester;
    public bool $semesterFormIsOpen = false;
    public StudentSubjectForm $subjectForm;
    public ?StudentSubject $subject;
    public bool $subjectFormIsOpen = false;

    public function mount()
    {
        $this->student->loadCount(['studentSemesters', 'studentSubjects']);
        $this->student->load([
            'studentSemesters' => [
                'studentSubjects' => ['courseSubject.subject', 'semesterSection.section'],
                'semesterSection.section',
                'semester',
            ],
        ]);
    }

    public function render()
    {

        $sections = [];

        if (isset($this->subject)) {
            $sections = Section::whereCourseId($this->subject->courseSubject->course->id)
                ->whereSemester($this->subject->courseSubject->courseSemester->semester)
                ->whereYearLevel($this->subject->studentSemester->year_level)
                ->lazy();
        }

        if (isset($this->semesterForm->semester_id) && isset($this->semesterForm->year_level)) {
            $sem      = Semester::whereId($this->semesterForm->semester_id)->first(['semester'])?->semester;
            $sections = Section::whereCourseId($this->student->course_id)
                ->whereSemester($sem)
                ->whereYearLevel($this->semesterForm->year_level)
                ->lazy();
        }

        $courseSubjects = $this->student->course->courseSubjects();

        if (isset($this->semester)) {
            $sem                       = $this->semester->semester->semester;
            $yearLevel                 = $this->semester->year_level;
            $exisitingCourseSubjectIds = $this->semester->studentSubjects
                ->pluck('course_subject_id')
                ->toArray();
            $courseSubjects = $courseSubjects
                ->whereHas('courseSemester',
                    fn($query) => $query->whereYearLevel($yearLevel)->whereSemester($sem)
                )
                ->whereNotIn('course_subjects.id', $exisitingCourseSubjectIds);
        }

        if (isset($this->semesterForm->course_semester_id)) {
            $courseSubjects = $courseSubjects->whereCourseSemesterId($this->semesterForm->course_semester_id);
        }

        $courseSubjects      = $courseSubjects->lazy();
        $existingSemesterIds = $this->student->studentSemesters->pluck('semester_id')->toArray();

        return view('livewire.pages.admin.students.student', [
            'sections'        => $sections,
            'semesters'       => Semester::whereNotIn('id', $existingSemesterIds)
                ->orderByDesc('school_year_id')
                ->orderByDesc('semester')
                ->lazy(),
            'courseSubjects'  => $courseSubjects,
            'courseSemesters' => $this->student->course->courseSemesters()
                ->lazy(),
        ])
            ->layout('components.layouts.admin');
    }

    public function updated(string $attr, mixed $value)
    {
        if (($attr === 'semesterForm.semester_id' ||
            $attr === 'semesterForm.year_level')
            && isset($this->semesterForm->semester_id)
            && isset($this->semesterForm->year_level)
        ) {
            $sem = Semester::whereId($this->semesterForm->semester_id)
                ->first(['semester'])?->semester;
            $courseSemId = CourseSemester::whereYearLevel($this->semesterForm->year_level)
                ->whereSemester($sem)
                ->first(['id'])?->id;

            if (isset($courseSemId)) {
                $this->semesterForm->course_semester_id = $courseSemId;
            }
        }
    }

    public function editSemester(StudentSemester $model)
    {
        $this->semesterForm->set($model);
        $this->openSemesterForm();
    }

    public function openSemesterForm()
    {
        $this->semesterFormIsOpen             = true;
        $this->semesterForm->include_base     = true;
        $this->semesterForm->include_subjects = true;
        $this->semesterForm->include_section  = true;
    }
    public function closeSemesterForm()
    {
        $this->semesterFormIsOpen = false;
        $this->semester           = null;
        $this->semesterForm->clear();
    }

    public function saveSemester()
    {
        if (! isset($this->semesterForm->student_id)) {
            $this->semesterForm->student_id = $this->student->id;
        }
        if (! isset($this->semesterForm->course_semester_id)) {
            CourseSemester::whereYearLevel($this->semesterForm->year_level);
            $this->semesterForm->course_semester_id = 0;
        }
        $this->semesterForm->submit();
        $this->closeSemesterForm();
    }

    public function openAddSubjectsForm(StudentSemester $model)
    {
        $this->semesterForm->set($model);
        $this->semester                         = $model;
        $this->semesterForm->course_ids         = [];
        $this->semesterForm->course_subject_ids = [];
        $this->openSemesterForm();
        $this->semesterForm->include_base    = false;
        $this->semesterForm->include_section = false;
    }

    public function editSemesterSection(StudentSemester $model)
    {
        $this->semesterForm->set($model);
        $this->semester                         = $model;
        $this->semesterForm->course_ids         = [];
        $this->semesterForm->course_subject_ids = [];
        $this->openSemesterForm();
        $this->semesterForm->include_base     = false;
        $this->semesterForm->include_subjects = false;
        $this->semesterForm->include_section  = true;
    }

    public function editSubject(StudentSubject $model)
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

    public function deleteSubject(StudentSubject $model)
    {
        $model->delete();
    }

    public function archiveSubject(StudentSubject $model)
    {
        $model->archive();
    }

    public function unarchiveSubject(StudentSubject $model)
    {
        $model->unarchive();
    }

    public function deleteSemester(StudentSemester $model)
    {
        $model->delete();
    }
}
