<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCourseSubject
 */
class CourseSubject extends Model
{
    use HasFactory;
    protected $table = 'course_subjects';

    public function students()
    {
        return $this->hasMany(Student::class, 'course_subject_id');
    }
}
