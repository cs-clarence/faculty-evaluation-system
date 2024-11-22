<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function courses()
    {
        return $this->belongsTo(Course::class);
    }

    public function subjects()
    {
        return $this->hasManyThrough(Subject::class, CourseSemesterSubject::class);
    }
}
