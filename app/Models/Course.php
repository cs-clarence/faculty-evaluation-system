<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 'course_name', 'department_id'];

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
}
