<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTeacher
 */
class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;
    protected $table = 'teachers';
    public $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacherSemesters()
    {
        return $this->hasMany(TeacherSemester::class);
    }

    public function teacherSubjects()
    {
        return $this->hasManyThrough(TeacherSubject::class, TeacherSemester::class);
    }
}
