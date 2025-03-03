<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;

/**
 * @mixin IdeHelperCourseSemester
 */
class CourseSemester extends Model
{
    use HasFactory;

    protected $table = 'course_semesters';

    protected $fillable = ['course_id', 'year_level', 'semester'];

    /**
     * Relationship with Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, CourseSubject::class)->withTimestamps();
    }

    public function courseSubjects(): HasMany
    {
        return $this->hasMany(CourseSubject::class);
    }

    public function studentSemesters()
    {
        return $this->hasMany(StudentSemester::class, 'course_semester_id', 'id');
    }

    public function hasDependents()
    {
        foreach ($this->courseSubjects as $cs) {
            if ($cs->hasDependents()) {
                return true;
            }
        }

        return false;
    }

    public function __tostring()
    {
        $yl         = Number::ordinal($this->year_level);
        $sem        = Number::ordinal($this->semester);
        $courseCode = $this->course->code;

        return "{$courseCode}, {$yl} Year - {$sem} Semester";
    }
}
