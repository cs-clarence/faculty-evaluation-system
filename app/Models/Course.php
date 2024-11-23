<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCourse
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'department_id'];
    protected $table = 'courses';

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_subject', 'course_id', 'subject_id');
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
}
