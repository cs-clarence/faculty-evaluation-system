<?php

namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTeacher
 */
class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;
    use Archivable {
        Archivable::archive as baseArchive;
        Archivable::unarchive as baseUnarchive;
    }

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

    public function hasDependents()
    {
        return $this->user->hasDependents();
    }

    public function delete()
    {
        $this->user->delete();
    }

    public function archive()
    {
        $this->user->archive();
    }

    public function unarchive()
    {
        $this->user->unarchive();
    }
}
