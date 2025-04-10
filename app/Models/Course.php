<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use App\Models\Traits\FullTextSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCourse
 */
class Course extends Model
{
    use HasFactory, Archivable, FullTextSearchable;

    protected $fillable = ['code', 'name', 'department_id'];
    protected $table    = 'courses';

    public function courseSubjects()
    {
        return $this->hasManyThrough(CourseSubject::class, CourseSemester::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship: A course has many course semesters.
     */
    public function courseSemesters()
    {
        return $this->hasMany(CourseSemester::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function hasDependents()
    {
        $sectionCount = isset($this->sections_count) ? $this->sections_count : $this->sections()->count();

        if ($sectionCount > 0) {
            return true;
        }

        return false;
    }

    public function __tostring()
    {
        return $this->name;
    }
}
