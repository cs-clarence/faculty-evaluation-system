<?php
namespace App\Livewire\Forms;

use App\Facades\Services\FormSubmissionService;
use App\Models\Form;
use App\Models\FormQuestionType;
use App\Models\FormSubmission;
use DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

class FormSubmissionForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    public ?int $teacher_id;
    public ?int $student_subject_id;
    public ?int $form_submission_period_id;
    public ?int $form_id;
    public ?array $questions;

    public function rules()
    {
        $unique = Rule::unique('form_submissions', 'teacher_id')
            ->where('student_subject_id', $this->student_subject_id)
            ->where('form_submission_period_id', $this->form_submission_period_id);

        $validators = [
            'teacher_id'                => ['required', 'integer', 'exists:teachers,id',
                isset($this->id) ? $unique->ignore($this->id) : $unique,
            ],
            'student_subject_id'        => ['required', 'integer', 'exists:student_subjects,id'],
            'form_submission_period_id' => ['required', 'integer', 'exists:form_submission_periods,id'],
        ];

        $formModel = Form::with(['sections.questions.options'])
            ->whereId($this->form_id)
            ->first();

        $additional = [];

        foreach ($formModel->sections as $section) {
            foreach ($section->questions as $question) {
                $key = "questions.{$question->id}";
                if ($question->type === FormQuestionType::Essay->value) {
                    $additional[$key] = ["required", "string", "max:1025"];
                } else if ($question->type === FormQuestionType::MultipleChoicesSingleSelect->value) {
                    $additional[$key] = ['required', 'integer', 'exists:form_question_options,id'];
                } else if ($question->type === FormQuestionType::MultipleChoicesMultipleSelect->value) {
                    $key              = "{$key}.*";
                    $additional[$key] = ['required', 'integer', 'exists:form_question_options,id'];
                } else {
                    throw new \Exception("Invalid question type '{$question->type}'");
                }
            }
        }

        return [ ...$validators, ...$additional];
    }

    //
    public function submit()
    {
        $this->validate();

        DB::transaction(function () {
            if (isset($this->id)) {
                FormSubmissionService::update(
                    $this->id,
                    $this->questions,
                );
            } else {
                FormSubmissionService::submit(
                    $this->form_id,
                    $this->form_submission_period_id,
                    $this->student_subject_id,
                    $this->teacher_id,
                    $this->questions,
                );
            }
        });
    }

    public function set(FormSubmission $model)
    {
        $model->load(['answers.selectedOptions']);
        $this->fill([
            'id'                        => $model->id,
            'teacher_id'                => $model->teacher_id,
            'student_subject_id'        => $model->student_subject_id,
            'form_submission_period_id' => $model->form_submission_period_id,
            'form_id'                   => $model->form_id,
            'questions'                 => $model->getAnswersArray(),
        ]);
    }
}
