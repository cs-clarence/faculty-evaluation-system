<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperCourseSubject
 */
class CourseSubject extends Pivot
{
    public $incrementing = true;
    protected $table     = 'course_subjects';
    protected $fillable  = ['subject_id', 'course_semester_id'];

    public function courseSemester(): BelongsTo
    {
        return $this->belongsTo(CourseSemester::class);
    }

    protected function course(): Attribute
    {
        return Attribute::make(fn() => $this->courseSemester->course);
    }

    public function semester(): HasOneThrough
    {
        return $this->hasOneThrough(Semester::class, CourseSemester::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function archive()
    {
        $this->archived_at = now();
        $this->save();
    }

    public function unarchive()
    {
        $this->archived_at = null;
        $this->save();
    }

    public function isArchived(): Attribute
    {
        return new Attribute(get: fn() => isset($this->archived_at));
    }

    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class, "course_subject_id", 'id');
    }

    public function hasDependents()
    {
        if (isset($this->student_subjects_count) && $this->student_subjects_count > 0) {
            return true;
        }
        if ($this->studentSubjects()->count() > 0) {
            return true;
        }

        return false;
    }
}
