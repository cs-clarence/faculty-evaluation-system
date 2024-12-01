<?php

namespace App\Services;

use App\Models\Form;
use App\Models\FormQuestionOption;
use App\Models\FormQuestionType;
use App\Models\FormSubmission;
use App\Models\FormSubmissionAnswer;
use App\Models\FormSubmissionPeriod;
use App\Models\StudentSubject;
use App\Models\Teacher;
use Log;

class FormSubmissionService
{
    private static function getFormId(Form | int $form)
    {
        return $form instanceof Form ? $form->id : $form;
    }

    private static function getFormSubmissionPeriodId(FormSubmissionPeriod | int $formSubmissionPeriod)
    {
        return $formSubmissionPeriod instanceof FormSubmissionPeriod ? $formSubmissionPeriod->id : $formSubmissionPeriod;
    }

    private static function getStudentSubjectId(StudentSubject | int $studentSubject)
    {
        return $studentSubject instanceof StudentSubject ? $studentSubject->id : $studentSubject;
    }

    private static function getTeacherId(Teacher | int $teacher)
    {
        return $teacher instanceof Teacher ? $teacher->id : $teacher;
    }

    private static function getForm(Form | int $form)
    {
        return $form instanceof Form ? $form : Form::with(['sections.questions' => [
            'options',
            'essayTypeConfiguration',
        ]])->whereId($form)->first();
    }

    private static function getFormSubmission(FormSubmission | int $formSubmission)
    {
        return $formSubmission instanceof FormSubmission
        ? $formSubmission
        : FormSubmission::whereId($formSubmission)->get();
    }

    private static function save(
        FormSubmission $formSubmission,
        array $answers,
    ) {
        Log::info(json_encode($answers));
        $form = self::getForm($formSubmission->form_id);

        $newAnswers = [];
        foreach ($form->sections as $section) {
            foreach ($section->questions as $question) {
                $value = $answers["{$question->id}"];
                if ($question->type === FormQuestionType::Essay->value) {
                    $config = $question->essayTypeConfiguration;
                    $newAnswers[] = new FormSubmissionAnswer([
                        'form_submission_id' => $formSubmission->id,
                        'form_question_id' => $question->id,
                        'value' => $config->value_scale_to,
                        'text' => $value,
                    ]);
                } else if ($question->type === FormQuestionType::MultipleChoicesSingleSelect->value) {
                    $option = FormQuestionOption::whereId($value)->first(['id', 'value', 'interpretation', 'name']);

                    $answer = new FormSubmissionAnswer([
                        'form_submission_id' => $formSubmission->id,
                        'form_question_id' => $question->id,
                        'value' => $option->value,
                        'text' => $option->name,
                        'interpretation' => $option->interpretation,
                    ]);
                    $answer->save();
                    $answer->selectedOptions()->delete();
                    $answer->selectedOptions()->create([
                        'form_submission_answer_id' => $answer->id,
                        'form_question_option_id' => $option->id,
                    ]);
                } else if ($question->type === FormQuestionType::MultipleChoicesMultipleSelect->value) {
                    throw new \Exception("Currently unsupported question type '{$question->type}'");
                } else {
                    throw new \Exception("Invalid question type '{$question->type}'");
                }
            }
        }

        if (count($answers) > 0) {
            $formSubmission->answers()->saveMany($newAnswers);
        }
    }

    public function submit(
        Form | int $form,
        FormSubmissionPeriod | int $formSubmissionPeriod,
        StudentSubject | int $studentSubject,
        Teacher | int $teacher,
        array $answers,
    ) {
        $formSubmission = new FormSubmission([
            'teacher_id' => self::getTeacherId($teacher),
            'student_subject_id' => self::getStudentSubjectId($studentSubject),
            'form_submission_period_id' => self::getFormSubmissionPeriodId($formSubmissionPeriod),
            'form_id' => self::getFormId($form),
        ]);

        $formSubmission->save();

        self::save($formSubmission, $answers);
    }

    public function update(
        FormSubmission | int $formSubmission,
        array $answers,
    ) {

        self::save($formSubmission, $answers);
    }
}
