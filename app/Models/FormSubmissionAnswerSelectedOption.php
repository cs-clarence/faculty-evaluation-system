<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionAnswerSelectedOption
 */
class FormSubmissionAnswerSelectedOption extends Model
{
    protected $table = 'form_submission_answer_selected_options';
    public $fillable = ['form_submission_answer_id', 'form_question_option_id'];
}
