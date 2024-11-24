<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCourse
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'department_id'];
    protected $table = 'courses';

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

    public function archive()
    {
        $this->archived_at = now();
        $this->save();
    }

    public function unarchive()
    {
        $this->archived_at = null;
        $this->save();
    }

    public function scopeWithoutArchived(Builder $builder)
    {
        $builder->whereNull('archived_at');
    }

    public function hasDependents()
    {
        $sectionCount = isset($this->sections_count) ? $this->sections_count : $this->sections()->count();

        if ($sectionCount > 0) {
            return true;
        }
    }
}
