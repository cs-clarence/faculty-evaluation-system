<?php
namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperTeacherSubject
 */
class TeacherSubject extends Pivot
{
    use Compoships;
    protected $table = 'teacher_subjects';

    public function semesterSection()
    {
        return $this->belongsTo(SemesterSection::class);
    }

    public function teacherSemester()
    {
        return $this->belongsTo(TeacherSemester::class);
    }

    public function teacher()
    {

    }
}
