<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperStudentSemester
 */
class StudentSemester extends Model
{
    //
    protected $table = 'student_semesters';
    public $fillable = ['student_id', 'semester_id'];

    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
