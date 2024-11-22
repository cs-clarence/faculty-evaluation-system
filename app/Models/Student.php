<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperStudent
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'student_name',
        'address',
    ];
    protected $table = 'students';

    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function courseSubject()
    {
        return $this->belongsTo(CourseSubject::class, 'course_subject_id');
    }
}
