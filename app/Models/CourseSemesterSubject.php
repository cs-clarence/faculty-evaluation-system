<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperCourseSemesterSubject
 */
class CourseSemesterSubject extends Model
{
    use HasFactory;
    protected $table = 'course_semester_subjects';
    public $fillable = ['subject_id', 'course_semester_id'];

    public function course_semester(): BelongsTo
    {
        return $this->belongsTo(CourseSemester::class);
    }

    public function subject(): HasOne
    {
        return $this->hasOne(Subject::class);
    }
}
