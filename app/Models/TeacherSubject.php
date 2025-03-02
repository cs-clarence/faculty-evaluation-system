<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperTeacherSubject
 */
class TeacherSubject extends Pivot
{
    use Compoships, Archivable;
    protected $table    = 'teacher_subjects';
    protected $fillable = ['teacher_semester_id', 'course_subject_id'];

    public function semesterSections()
    {
        return $this->belongsToMany(SemesterSection::class, 'teacher_subject_semester_sections', 'teacher_subject_id', 'semester_section_id', 'id', 'id');
    }

    public function teacherSemester()
    {
        return $this->belongsTo(TeacherSemester::class);
    }

    public function hasDependents()
    {
        return false;
    }

    public function courseSubject()
    {
        return $this->belongsTo(CourseSubject::class);
    }

    protected function subject(): Attribute
    {
        return Attribute::make(get: fn() => $this->courseSubject->subject)->shouldCache();
    }
}
