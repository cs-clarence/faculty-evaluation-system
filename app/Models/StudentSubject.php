<?php
namespace App\Models;

use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperStudentSubject
 */
class StudentSubject extends Pivot
{
    use Compoships;
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

    public function teacherSubject()
    {
        return $this->hasOne(TeacherSubject::class,
            ['semester_section_id', 'course_subject_id'],
            ['semester_section_id', 'course_subject_id']);
    }

    protected function subject(): Attribute
    {
        return Attribute::make(fn() => $this->courseSubject->subject)->shouldCache();
    }

    public function formSubmissionSubject(): HasOne
    {
        return $this->hasOne(FormSubmissionSubject::class, 'student_subject_id', 'id');
    }

    public function subjectName(): Attribute
    {
        return Attribute::make(get: fn() => $this->courseSubject?->subject?->name);
    }

    public function subjectCode(): Attribute
    {
        return Attribute::make(get: fn() => $this->courseSubject?->subject?->code);
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
}
