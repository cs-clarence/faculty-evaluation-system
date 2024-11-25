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
        'student_number',
        'address',
    ];
    protected $table = 'students';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentSemesters()
    {
        return $this->hasMany(StudentSemester::class);
    }

    public function teacherSubjects()
    {
        return $this->hasManyThrough(StudentSubject::class, StudentSemester::class);
    }
}
