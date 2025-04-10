<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use App\Models\Traits\FullTextSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSubject
 */
class Subject extends Model
{
    use HasFactory, Archivable, FullTextSearchable;

    protected $table = 'subjects';

    protected $fillable = [
        'code',
        'name',
    ];

    public function courseSubjects(): HasMany
    {
        return $this->hasMany(CourseSubject::class);
    }

    public function courseSemesters(): BelongsToMany
    {
        return $this->belongsToMany(CourseSemester::class, CourseSubject::class)
            ->using(CourseSubject::class);
    }

    public function teacherSemesters(): BelongsToMany
    {
        return $this->belongsToMany(TeacherSemester::class, TeacherSubject::class)
            ->using(TeacherSubject::class);
    }

    public function studentSemesters(): BelongsToMany
    {
        return $this->belongsToMany(StudentSemester::class, StudentSemester::class)
            ->using(StudentSubject::class);
    }

    public function hasDependents()
    {
        $courseCount = isset($this->course_semesters_count) ? $this->course_semesters_count : $this->courseSemesters()->count();

        if ($courseCount > 0) {
            return true;
        }

        return false;
    }

    public function __tostring()
    {
        return "{$this->name} ({$this->code})";
    }
}
