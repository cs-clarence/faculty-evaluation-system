<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTeacherSemester
 */
class TeacherSemester extends Model
{
    //
    protected $table    = 'teacher_semesters';
    protected $fillable = ['teacher_id', 'semester_id'];

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function hasDependents()
    {
        return false;
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
