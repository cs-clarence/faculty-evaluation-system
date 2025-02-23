<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionDepartment
 */
class FormSubmissionDepartment extends Model
{
    protected $fillable = ['form_submission_id', 'department_id'];
    protected $table    = 'form_submission_departments';
    public $timestamps  = false;

    public function formSubmission()
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
