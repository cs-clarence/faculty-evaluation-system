<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperStudentSubject
 */
class StudentSubject extends Pivot
{
    use Compoships, Archivable;
    public $incrementing = true;
    //
    protected $table = 'student_subjects';
    public $fillable = ['student_semester_id', 'course_subject_id', 'semester_section_id'];

    public function studentSemester()
    {
        return $this->belongsTo(StudentSemester::class);
    }

    public function semesterSection()
    {
        return $this->belongsTo(SemesterSection::class);
    }

    public function courseSubject()
    {
        return $this->belongsTo(CourseSubject::class);
    }

    public function getTeacher()
    {
        return Teacher::query()
            ->whereHas('teacherSemesters',
                fn($q) =>
                $q->where('semester_id', $this->studentSemester->semester_id)
                    ->whereHas('teacherSubjects',
                        fn($q) =>
                        $q->where('course_subject_id', $this->id)
                            ->whereHas('semesterSections',
                                fn($q2) =>
                                $q2->where('semester_section_id', $this->semester_section_id))
                    )
            )
            ->first();
    }

    protected function teacher(): Attribute
    {
        return Attribute::make(get: fn() => $this->getTeacher())
            ->shouldCache();
    }

    protected function subject(): Attribute
    {
        return Attribute::make(fn() => $this->courseSubject->subject)
            ->shouldCache();
    }

    public function formSubmissionSubject(): HasOne
    {
        return $this->hasOne(FormSubmissionSubject::class, 'student_subject_id', 'id');
    }

    public function subjectName(): Attribute
    {
        return Attribute::make(get: fn() => $this->courseSubject->subject->name);
    }

    public function subjectCode(): Attribute
    {
        return Attribute::make(get: fn() => $this->courseSubject->subject->code);
    }

    public function courseName(): Attribute
    {
        return Attribute::make(get: fn() => $this->courseSubject?->courseSemester?->course?->name);
    }

    public function courseCode(): Attribute
    {
        return Attribute::make(get: fn() => $this->courseSubject?->courseSemester?->course?->code);
    }

    protected function departmentName(): Attribute
    {
        return Attribute::make(get: fn() =>
            $this->courseSubject?->courseSemester?->course?->department?->name
        );
    }

    protected function departmentCode(): Attribute
    {
        return Attribute::make(get: fn() =>
            $this->courseSubject?->courseSemester?->course?->department?->code
        );
    }

    public function hasDependents()
    {
        return false;
    }
}
