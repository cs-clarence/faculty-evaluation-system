<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;

/**
 * @mixin IdeHelperSemester
 */
class Semester extends Model
{
    protected $table = 'semesters';
    public $fillable = ['semester', 'school_year_id'];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function semesterSections(): HasMany
    {
        return $this->hasMany(SemesterSection::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, SemesterSection::class);
    }

    public function studentSemesters()
    {
        return $this->hasMany(StudentSemester::class);
    }

    public function formSubmissionPeriodSemesters(): HasMany
    {
        return $this->hasMany(FormSubmissionPeriodSemester::class);
    }

    public function __tostring()
    {
        $sy  = isset($this->schoolYear) ? $this->schoolYear : $this->schoolYear();
        $ord = Number::ordinal($this->semester);

        return "{$ord} Semester, SY: {$sy}";
    }
}
