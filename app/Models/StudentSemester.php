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
    public $fillable = ['student_id', 'semester_id', 'year_level', 'semester_section_id', 'course_semester_id'];

    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class, 'student_semester_id', 'id');
    }

    public function semesterSection()
    {
        return $this->belongsTo(SemesterSection::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function courseSemester()
    {
        return $this->belongsTo(CourseSemester::class, 'course_semester_id', 'id');
    }

    public function hasDependents()
    {
        return false;
    }
}
