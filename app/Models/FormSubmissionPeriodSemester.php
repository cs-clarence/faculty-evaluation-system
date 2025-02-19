<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionPeriodSemester
 */
class FormSubmissionPeriodSemester extends Model
{
    public $timestamps  = false;
    protected $fillable = ['form_submission_period_id', 'semester_id'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function formSubmissionPeriod()
    {
        return $this->belongsTo(FormSubmissionPeriod::class);
    }
}
