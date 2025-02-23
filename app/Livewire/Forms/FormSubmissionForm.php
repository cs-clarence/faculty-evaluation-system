<?php
namespace App\Livewire\Forms;

use App\Facades\Services\FormSubmissionService;
use App\Models\Form;
use App\Models\FormQuestionType;
use App\Models\FormSubmission;
use DB;
use Livewire\Attributes\Locked;

class FormSubmissionForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    public ?int $evaluatee_id;
    public ?int $evaluator_id;
    public ?int $course_subject_id;
    public ?int $student_subject_id;
    public ?int $form_submission_period_id;
    public ?array $questions;

    public function rules()
    {
        $uniqueSubmission = function (string $attribute, mixed $data, callable $fail) {
            $query = FormSubmission::where($attribute, $data)
                ->where('evaluator_id', $this->evaluator_id)
                ->where('form_submission_period_id', $this->form_submission_period_id);

            if (isset($this->course_subject_id)) {
                $query = $query->whereHas('formSubmissionSubject', fn($q) => $q->whereCourseSubjectId($this->course_subject_id));
            }

            if (isset($this->student_subject_id)) {
                $query = $query->whereHas('formSubmissionSubject', fn($q) => $q->whereStudentSubjectId($this->student_subject_id));
            }

            if (isset($this->id)) {
                $query = $query->whereNot('id', $this->id);
            }

            if ($query->exists()) {
                $fail('A form submission already exists for this evaluatee');
            }
        };

        $validators = [
            'evaluatee_id'              => ['required', 'integer', 'exists:users,id', $uniqueSubmission],
            'evaluator_id'              => ['required', 'integer', 'exists:users,id'],
            'form_submission_period_id' => ['required', 'integer', 'exists:form_submission_periods,id'],
        ];

        $formModel = Form::with(['sections.questions.options'])
            ->whereHas('submissionPeriods', fn($q) => $q->whereId($this->form_submission_period_id))
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
                    $this->form_submission_period_id,
                    $this->evaluator_id,
                    $this->evaluatee_id,
                    $this->questions,
                    $this->course_subject_id,
                    $this->student_subject_id,
                );
            }
        });
    }

    /**
     * @param FormSubmission $model
     */
    public function set(mixed $model)
    {
        $model->load(['answers.selectedOptions']);
        $this->fill([
            'id'                        => $model->id,
            'evaluatee_id'              => $model->evaluatee_id,
            'evaluator_id'              => $model->evaluator_id,
            'course_subject_id'         => $model->formSubmissionSubject?->course_subject_id,
            'student_subject_id'        => $model->formSubmissionSubject?->student_subject_id,
            'form_submission_period_id' => $model->form_submission_period_id,
            'questions'                 => $model->getAnswersArray(),
        ]);
    }
}
