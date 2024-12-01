<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperStudentSubject
 */
class StudentSubject extends Pivot
{
    public $incrementing = true;
    //
    protected $table = 'student_subjects';
    public $fillable = ['student_semester_id', 'course_subject_id', 'semester_section_id'];

    public function studentSemester()
    {
        return $this->belongsTo(StudentSemester::class);
    }
}
