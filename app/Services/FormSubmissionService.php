<?php
namespace App\Services;

use App\Features\ChatBot\Services\ChatCompleter;
use App\Features\ChatBot\Types\Message\Message;
use App\Models\CourseSubject;
use App\Models\Department;
use App\Models\Form;
use App\Models\FormQuestionEssayTypeConfiguration;
use App\Models\FormQuestionOption;
use App\Models\FormQuestionType;
use App\Models\FormSubmission;
use App\Models\FormSubmissionAnswer;
use App\Models\FormSubmissionPeriod;
use App\Models\StudentSubject;
use App\Models\User;
use Exception;
use Log;

class FormSubmissionService
{
    public function __construct(private ChatCompleter $chat)
    {
    }

    private static function createSystemMessage(?FormQuestionEssayTypeConfiguration $config, $evaluator, $evaluatee)
    {
        $scaleFrom = $config?->value_scale_from ?: 0;
        $scaleTo   = $config?->value_scale_to ?: 0;
        return Message::system(<<<TXT
            You are a helpful assistant. You evaluate essay type answers to questions
            and determine the value from the scale from {$scaleFrom} to {$scaleTo}.
            The value can be a float value.
            You also determine the interpretation of the answer where it's Good or Bad.
            Also express the reason why you decided to give that score.
            You can only answer in json encoded format.
            The questions are answered by a {$evaluator} used to evaluate a {$evaluatee}.
            The value should the score of the {$evaluatee} in relation to the question
            used to evaluate them.

            Examples:
            Question: What are the characteristics that you like to your teacher?
            Essay Answer: The teacher really knows how to teach.
            Your Response:
            {
                "value": 3.73,
                "interpretation": "Good",
                "reason": "The teacher really knows how to teach."
            }
        TXT);
    }

    public static function createUserMessage(string $question, string $answer)
    {
        return Message::user(<<<TXT
        Question: {$question}
        Essay Answer: {$answer}
        TXT);
    }

    private static function randomBetweenFloat(float $min, float $max)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    private static function getHalf(float $min, float $max)
    {
        $diff = $max - $min;
        return $min + ($diff / 2);
    }

    private function getInterpretation(?FormQuestionEssayTypeConfiguration $config,
        string $question,
        string $answer,
        string $evaluator,
        string $evaluatee,
    ) {

        $systemMessage = self::createSystemMessage($config, $evaluator, $evaluatee);
        $userMessage   = self::createUserMessage(
            $question,
            $answer,
        );
        try {
            $response = $this->chat->complete([$systemMessage, $userMessage],
                false,
                [
                    'response_format' => ['type' => 'json_object'],
                ]
            );
            $text       = $response->getText();
            $aiResponse = json_decode($text, true);

            return $aiResponse;
        } catch (Exception $e) {
            Log::error($e);
            $half   = self::getHalf($config->value_scale_from, $config->value_scale_to);
            $random = self::randomBetweenFloat($config->value_scale_from, $config->value_scale_to);
            return [
                'value'          => $random,
                'interpretation' => $random > $half ? 'Good' : 'Bad',
                'reason'         => '',
            ];
        }
    }

    private static function getFormSubmissionPeriodId(FormSubmissionPeriod | int $formSubmissionPeriod)
    {
        return $formSubmissionPeriod instanceof FormSubmissionPeriod ? $formSubmissionPeriod->id : $formSubmissionPeriod;
    }

    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    private static function getUser(User | int $user)
    {
        return $user instanceof User ? $user : User::whereId($user)->first();
    }

    private static function getCourseSubjectId(CourseSubject | int $courseSubject)
    {
        return $courseSubject instanceof CourseSubject ? $courseSubject->id : $courseSubject;
    }

    private static function getStudentSubjectId(StudentSubject | int $studentSubject)
    {
        return $studentSubject instanceof StudentSubject ? $studentSubject->id : $studentSubject;
    }

    private static function getDepartmentId(Department | int $department)
    {
        return $department instanceof Department ? $department->id : $department;
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

    private function save(
        FormSubmission $formSubmission,
        array $answers,
    ) {
        $form = self::getForm($formSubmission->submissionPeriod->form_id);

        $evaluateeRole = $formSubmission->submissionPeriod->evaluateeRole->display_name;
        $evaluatorRole = $formSubmission->submissionPeriod->evaluatorRole->display_name;

        $formSubmission->answers()->delete();
        foreach ($form->sections as $section) {
            foreach ($section->questions as $question) {
                $value = $answers["{$question->id}"];
                if ($question->type === FormQuestionType::Essay->value) {
                    $config         = $question->essayTypeConfiguration;
                    $interpretation = $this->getInterpretation(
                        $config,
                        $question->title,
                        $value,
                        $evaluatorRole,
                        $evaluateeRole
                    );

                    $answer = FormSubmissionAnswer::create([
                        'form_submission_id' => $formSubmission->id,
                        'form_question_id'   => $question->id,
                        'value'              => $interpretation['value'],
                        'text'               => $value,
                        'interpretation'     => $interpretation['interpretation'],
                        'reason'             => $interpretation['reason'],
                    ]);
                } else if ($question->type === FormQuestionType::MultipleChoicesSingleSelect->value) {
                    $option = FormQuestionOption::whereId($value)->first(['id', 'value', 'interpretation', 'label']);

                    $answer = FormSubmissionAnswer::create([
                        'form_submission_id' => $formSubmission->id,
                        'form_question_id'   => $question->id,
                        'value'              => $option->value,
                        'text'               => $option->label,
                        'interpretation'     => $option->interpretation,
                    ]);
                    $answer->selectedOptions()->create([
                        'form_submission_answer_id' => $answer->id,
                        'form_question_option_id'   => $option->id,
                    ]);
                } else if ($question->type === FormQuestionType::MultipleChoicesMultipleSelect->value) {
                    $options = FormQuestionOption::whereId($value)->get(['id', 'value', 'interpretation', 'label']);

                    $answer = FormSubmissionAnswer::create([
                        'form_submission_id' => $formSubmission->id,
                        'form_question_id'   => $question->id,
                        'value'              => min($options->sum('value'), 0),
                        'text'               => $options->pluck('label')->implode(', '),
                        'interpretation'     => $options->pluck('interpretation')->implode('. '),
                    ]);
                    $answer->selectedOptions()->createMany(
                        $options->map(fn($o) => [
                            'form_submission_answer_id' => $answer->id,
                            'form_question_option_id'   => $option->id,
                        ])
                    );
                } else {
                    throw new \Exception("Invalid question type '{$question->type}'");
                }
            }
        }
    }

    public function submit(
        FormSubmissionPeriod | int $formSubmissionPeriod,
        User | int $evaluator,
        User | int $evaluatee,
        array $answers,
        CourseSubject | int | null $courseSubject = null,
        StudentSubject | int | null $studentSubject = null,
        Department | int | null $department = null,
    ) {
        $evaluatee = self::getUser($evaluatee);
        $evaluator = self::getUser($evaluator);
        if ($evaluatee->isTeacher() && $evaluator->isTeacher() &&
            ($evaluatee->teacher->department_id !== $evaluator->teacher->department_id)
        ) {
            throw new Exception('Departments do not match');
        }

        if ($evaluatee->isTeacher() && $evaluator->isDean() &&
            ($evaluatee->teacher->department_id !== $evaluator->dean->department_id)
        ) {
            throw new Exception('Departments do not match');
        }

        $formSubmission = new FormSubmission([
            'evaluatee_id'              => self::getUserId($evaluatee),
            'evaluator_id'              => self::getUserId($evaluator),
            'form_submission_period_id' => self::getFormSubmissionPeriodId($formSubmissionPeriod),
        ]);

        $formSubmission->save();
        if (isset($courseSubject) || isset($studentSubject)) {
            $formSubmission->formSubmissionSubject()->create([
                'course_subject_id'  => isset($courseSubject)
                ? self::getCourseSubjectId($courseSubject)
                : (
                    isset($studentSubject)
                    ? ($studentSubject instanceof StudentSubject
                        ? $studentSubject->course_subject_id
                        : StudentSubject::whereId($studentSubject)->first(['course_subject_id'])?->course_subject_id)
                    : null
                ),
                'student_subject_id' => isset($studentSubject) ? self::getStudentSubjectId($studentSubject) : null,
            ]);
        }

        $departmentId = null;
        if (isset($department)) {
            $departmentId = self::getDepartmentId($department);
        } else if ($evaluatee->isTeacher()) {
            $departmentId = $evaluatee->teacher->department_id;
        } else if ($evaluatee->isDean()) {
            $departmentId = $evaluatee->dean->department_id;
        }

        if (isset($departmentId)) {
            $formSubmission->formSubmissionDepartment()->create([
                'department_id' => $departmentId,
            ]);
        }

        $this->save($formSubmission, $answers);
    }

    public function update(
        FormSubmission | int $formSubmission,
        array $answers,
    ) {
        $formSubmission = $formSubmission instanceof FormSubmission ? $formSubmission : FormSubmission::whereId($formSubmission)->first();
        $this->save($formSubmission, $answers);
    }
}
