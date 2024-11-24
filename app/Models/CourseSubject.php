<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperCourseSubject
 */
class CourseSubject extends Pivot
{
    public $incrementing = true;
    protected $table = 'course_subjects';
    protected $fillable = ['subject_id', 'course_semester_id'];

    public function courseSemester(): BelongsTo
    {
        return $this->belongsTo(CourseSemester::class);
    }

    public function semester(): HasOneThrough
    {
        return $this->hasOneThrough(Semester::class, CourseSemester::class);
    }

    public function subject(): HasOne
    {
        return $this->hasOne(Subject::class);
    }
}
