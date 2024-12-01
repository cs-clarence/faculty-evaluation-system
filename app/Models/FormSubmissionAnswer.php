<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionAnswer
 */
class FormSubmissionAnswer extends Model
{
    protected $table = 'form_submission_answers';
    public $fillable = ['form_submission_id', 'form_question_id', 'value', 'text', 'interpretation'];

    public function selectedOptions()
    {
        return $this->hasMany(FormSubmissionAnswerSelectedOption::class);
    }
}
