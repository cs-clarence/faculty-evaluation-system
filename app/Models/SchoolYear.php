<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSchoolYear
 */
class SchoolYear extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolYearFactory> */
    use HasFactory;
    protected $table = 'school_years';
    protected $fillable = ['year_start', 'year_end'];

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
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

    public function hasDependents()
    {
        $semesters = $this->semesters()->withCount(['sections', 'formSubmissionPeriods'])->get();

        foreach ($semesters as $semester) {
            if ($semester->sections_count > 0 || $semester->form_submission_periods_count > 0) {
                return true;
            }
        }

        return false;
    }

    public function __tostring()
    {
        return "{$this->year_start} - {$this->year_end}";
    }
}
