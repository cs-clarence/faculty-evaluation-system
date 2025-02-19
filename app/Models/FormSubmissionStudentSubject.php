<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionStudentSubject
 */
class FormSubmissionStudentSubject extends Model
{
    protected $fillable = ['form_submission_id', 'student_subject_id'];

    public function formSubmission()
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function studentSubject()
    {
        return $this->belongsTo(StudentSubject::class);
    }
}
