<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperStudent
 */
class Student extends Model
{
    use HasFactory;
    use Archivable {
        Archivable::archive as baseArchive;
        Archivable::unarchive as baseUnarchive;
    }

    protected $fillable = [
        'user_id',
        'student_number',
        'address',
        'course_id',
        'starting_school_year_id',
    ];

    protected $table = 'students';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studentSemesters()
    {
        return $this->hasMany(StudentSemester::class, 'student_id', 'id');
    }

    public function studentSubjects()
    {
        return $this->hasManyThrough(StudentSubject::class, StudentSemester::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'starting_school_year_id');
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
