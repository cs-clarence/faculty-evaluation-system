<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionSubject
 */
class FormSubmissionSubject extends Model
{
    protected $fillable = ['form_submission_id', 'course_subject_id', 'student_subject_id'];
    protected $table    = 'form_submission_subjects';
    public $timestamps  = false;

    public function formSubmission()
    {
        return $this->belongsTo(FormSubmission::class);
    }

    /**
     * Summary of subject
     * @return Attribute<Subject>
     */
    protected function subject(): Attribute
    {
        return Attribute::make(fn() => $this->courseSubject->subject)->shouldCache();
    }

    /**
     * Summary of subject
     * @return Attribute<Course>
     */
    protected function course(): Attribute
    {
        return Attribute::make(fn() => $this->courseSubject->course)->shouldCache();
    }

    public function courseSubject()
    {
        return $this->belongsTo(CourseSubject::class);
    }

    public function studentSubject()
    {
        return $this->belongsTo(StudentSubject::class);
    }
}
