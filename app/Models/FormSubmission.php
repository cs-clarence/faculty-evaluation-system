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
    protected $table    = 'form_submissions';
    protected $fillable = ['evaluator_id', 'evaluatee_id', 'form_submission_period_id'];
    protected $appends  = ['rating', 'total_weight'];

    public function studentSubject()
    {
        return $this->belongsTo(StudentSubject::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluatee()
    {
        return $this->belongsTo(User::class, 'evaluatee_id');
    }

    public function submissionPeriod()
    {
        return $this->belongsTo(FormSubmissionPeriod::class, 'form_submission_period_id');
    }

    public function answers()
    {
        return $this->hasMany(FormSubmissionAnswer::class);
    }

    public function getAnswersArray()
    {
        $values = [];

        foreach ($this->answers as $answer) {
            $questionId  = $answer->form_question_id;
            $questionKey = "{$questionId}";
            $question    = FormQuestion::whereId($questionId)->first(['type']);

            if ($question->type === FormQuestionType::Essay->value) {
                $values[$questionKey] = $answer->text;
            } else if ($question->type === FormQuestionType::MultipleChoicesSingleSelect->value) {
                $optionId             = $answer->selectedOptions->first()->form_question_option_id;
                $values[$questionKey] = $optionId;
            } else if ($question->type === FormQuestionType::MultipleChoicesMultipleSelect->value) {
                $optionIds              = $answer->selectedOptions->pluck('form_question_option_id');
                $values[$questionKey][] = $optionIds;
            } else {
                throw new \Exception("Invalid question type '{$question->type}'");
            }
        }

        return $values;
    }

    public function getTotalWeight()
    {

        return $this->submissionPeriod->form->total_weight;
    }

    protected function totalWeight(): Attribute
    {
        return Attribute::make(get: fn() => $this->getTotalWeight())->shouldCache();
    }

    public function getRating()
    {
        $count       = 0;
        $current     = 0;
        $answers     = $this->answers;
        $questions   = $this->submissionPeriod->form->questions;
        $totalWeight = $this->total_weight;
        foreach ($answers as $answer) {
            $q        = $questions->filter(fn($x) => $x->id === $answer->form_question_id)->first();
            $weight   = $q->weight;
            $value    = $answer->value;
            $maxValue = $answer->max_value;

            $current += (($value / $maxValue) * 100) * $weight;
            ++$count;
        }
        return round($current / $totalWeight, 2);
    }

    public function getValue(int $formQuestionId)
    {
        if (isset($this->answers) && count($this->answers) > 0) {
            return $this->answers->filter(fn($i) => $i->form_question_id === $formQuestionId)->first()?->value;
        } else {
            return $this->answers()->whereFormQuestionId($formQuestionId)->first()->value;
        }
    }

    public function getWeightedValue(int $formQuestionId)
    {
        if (isset($this->answers) && count($this->answers) > 0) {
            $answer = $this->answers->filter(fn($i) => $i->form_question_id === $formQuestionId)->first();
        } else {
            $answer = $this->answers()->whereFormQuestionId($formQuestionId)->first();
        }
        $rawValue     = $answer->value;
        $maxValue     = $answer->max_value;
        $totalWeights = $this->total_weight;

        return round((($rawValue / $maxValue) * 100) / $totalWeights, 2);
    }

    public function getInterpretation(int $formQuestionId)
    {
        if (isset($this->answers) && count($this->answers) > 0) {
            return $this->answers->filter(fn($i) => $i->form_question_id === $formQuestionId)->first()?->interpretation;
        } else {
            return $this->answers()->whereFormQuestionId($formQuestionId)->first()->interpretation;
        }
    }

    public function getReason(int $formQuestionId)
    {
        if (isset($this->answers) && count($this->answers) > 0) {
            return $this->answers->filter(fn($i) => $i->form_question_id === $formQuestionId)->first()?->reason;
        } else {
            return $this->answers()->whereFormQuestionId($formQuestionId)->first()->reason;
        }
    }

    protected function rating(): Attribute
    {
        return Attribute::make(get: fn() => $this->getRating())->shouldCache();
    }

    public function getSummary()
    {
        $breakdown = [];

        foreach ($this->answers as $answer) {
            $breakdown[] = [
                'question'       => $answer->formQuestion->title,
                'value'          => $answer->value,
                'text'           => $answer->text,
                'interpretation' => $answer->interpretation,
                'percentage'     => $this->getWeightedValue($answer->formQuestion->id),
            ];
        }

        return $breakdown;
    }

    protected function summary(): Attribute
    {
        return Attribute::make(get: fn() => $this->getSummary())->shouldCache();
    }
}
