<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSemester extends Model
{
    use HasFactory;

    protected $table = 'course_semester';

    protected $fillable = ['course_id', 'year_level', 'semester'];

    /**
     * Relationship with Course.
     */
    public function courses()
    {
        return $this->belongsTo(Course::class);
    }
}
