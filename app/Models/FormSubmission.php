<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmission
 */
class FormSubmission extends Model
{
    //
    protected $table = 'form_submissions';
    public $fillable = ['student_subject_id', 'teacher_id', 'form_submission_period_id', 'form_id'];

    public function studentSubject()
    {
        return $this->belongsTo(StudentSubject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function submissionPeriod()
    {
        return $this->belongsTo(FormSubmissionPeriod::class, 'form_submission_period_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function answers()
    {
        return $this->hasMany(FormSubmissionAnswer::class);
    }

    public function getAnswersArray()
    {
        $values = [];

        foreach ($this->answers as $answer) {
            $questionId = $answer->form_question_id;
            $questionKey = "{$questionId}";
            $question = FormQuestion::whereId($questionId)->first(['type']);

            if ($question->type === FormQuestionType::Essay->value) {
                $values[$questionKey] = $answer->text;
            } else if ($question->type === FormQuestionType::MultipleChoicesSingleSelect->value) {
                $optionId = $answer->selectedOptions->first()->form_question_option_id;
                $values[$questionKey] = $optionId;
            } else if ($question->type === FormQuestionType::MultipleChoicesMultipleSelect->value) {
                $optionIds = $answer->selectedOptions->pluck('form_question_option_id');
                $values[$questionKey][] = $optionIds;
            } else {
                throw new \Exception("Invalid question type '{$question->type}'");
            }
        }

        return $values;
    }

    public function getRating()
    {
        $count = 0;
        $current = 0;
        foreach ($this->answers as $answer) {
            $q = FormQuestion::whereId($answer->form_question_id)->first(['type']);
            $value = $answer->value;
            if ($q->type === FormQuestionType::Essay->value) {
                $config = FormQuestionEssayTypeConfiguration::whereFormQuestionId($answer->form_question_id)
                    ->first(['value_scale_from', 'value_scale_to']);

                ++$count;
                $current += ($value / $config->value_scale_to) * 100;
            } else if ($q->type === FormQuestionType::MultipleChoicesSingleSelect->value) {
                $maxValue = FormQuestionOption::whereFormQuestionId($answer->form_question_id)->max('value');
                $current += ($value / $maxValue) * 100;

                ++$count;
            } else if ($q->type === FormQuestionType::MultipleChoicesMultipleSelect->value) {
                throw new \Exception("Unsupported question type '{$q}'");
            } else {
                throw new \Exception("Invalid question type '{$q}'");
            }
        }
        return round($current / $count, 2);
    }

    public function getValue(int $formQuestionId)
    {
        if (isset($this->answers) && count($this->answers) > 0) {
            return $this->answers->filter(fn($i) => $i->form_question_id === $formQuestionId)->first()?->value;
        } else {
            return $this->answers()->whereFormQuestionId($formQuestionId)->get()->value;
        }
    }

    public function rating(): Attribute
    {
        return Attribute::make(get: fn() => $this->getRating());
    }
}
