<?php
namespace App\Services;

use App\Features\ChatBot\Services\ChatCompleter;
use App\Features\ChatBot\Types\Message\Message;
use App\Models\CourseSubject;
use App\Models\FormQuestionType;
use App\Models\FormSubmission;
use App\Models\LlmResponse;
use App\Models\Semester;
use App\Models\Summaries\DetailedEvaluationSummary;
use App\Models\Summaries\EvaluationSummary;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PhpParser\Error;

class EvaluationSummaryService
{
    public function __construct(private ChatCompleter $chat)
    {
    }

    private static function getUserId(User | int $user): int
    {
        return $user instanceof User ? $user->id : $user;
    }

    private static function getSummary(int $id, User $user, Semester $semester, ?CourseSubject $courseSubject, $formSubmissions)
    {
        return new EvaluationSummary(
            $id,
            $user,
            $semester,
            $courseSubject ?? null,
            $formSubmissions->average(fn($x) => $x->rating),
            $formSubmissions->count(),
        );
    }

    private static function createSystemMessage(User $user, $formSubmissions, $questionMap)
    {
        $count             = count($formSubmissions);
        $maxSentencesCount = floor(count($questionMap) / 2);
        return Message::system(<<<TXT
        You are a helpful bot that summarizes the evaluations of a '{$user->role->name}'.
        You will be given a bunch of questions with provided answers that are either essay type or multiple choices with tally.
        Your job is to summarize all is to summarize the questions and answers into a maximum of {$maxSentencesCount} sentences. Don't mention specific figures when it comes to multiple choices, just summarize in a textual form. The response should pertain to the {$user->role->name} being evaluated who is {$user->name}. The {$user->role->name} received a total of {$count} evaluations. The summary should focus on the findings from the answers provided, don't include too much details such as type of questions and and figures.

        Refer to the people who evaluated the teacher as evaluators.
        TXT);
    }

    public static function createUserMessage(array $questionMap)
    {
        ob_start();

        foreach ($questionMap as $q => $details) {
            switch ($details['type']) {
                case FormQuestionType::Essay->value:
                    echo "Question: {$q}\n";
                    echo "Answer: \n";
                    foreach ($details['answers'] as $idx => $value) {
                        $num = $idx + 1;
                        echo "{$num}. {$value}\n";
                    }
                    break;
                case FormQuestionType::MultipleChoicesMultipleSelect->value:
                case FormQuestionType::MultipleChoicesSingleSelect->value:
                    echo "Question: {$q}\n";
                    echo "Answers Tally: \n";
                    foreach ($details['answers'] as $label => $value) {
                        echo "{$label}: {$value}\n";
                    }

                    break;
            }
            echo "-------------------------------------------------------\n";
        }

        $userMsg = ob_get_clean();

        return Message::user($userMsg);
    }

    /**
     * Summary of getOverallSummary
     * @param Collection<FormSubmission> $formSubmissions
     * @return void
     */
    private function getOverallSummary(User $evaluatee, $formSubmissions): string
    {
        $questionMap = [];
        foreach ($formSubmissions as $f) {
            foreach ($f->answers as $answer) {
                $q    = $answer->formQuestion->title;
                $type = $answer->formQuestion->type;

                $key = "$q (type: $type)";

                if (! isset($questionMap[$key])) {
                    $questionMap[$key] = [
                        'type'    => $type,
                        'answers' => [],
                    ];
                }
                switch ($type) {
                    case FormQuestionType::Essay->value:
                        $questionMap[$key]['answers'][] = $answer->text;
                        break;
                    case FormQuestionType::MultipleChoicesMultipleSelect->value:
                    case FormQuestionType::MultipleChoicesSingleSelect->value:
                        foreach ($answer->formQuestion->options as $option) {
                            if (! isset($questionMap[$key]['answers'][$option->label])) {
                                $questionMap[$key]['answers'][$option->label] = 0;
                            }
                        }

                        foreach ($answer->selectedOptions as $selected) {
                            $questionMap[$key]['answers'][$selected->option->label]++;
                        }
                        break;
                    default:
                        throw new Error("Invalid question type '$type'");
                }
            }
        }

        $systemMessage = self::createSystemMessage($evaluatee, $formSubmissions, $questionMap);
        $userMessage   = self::createUserMessage(
            $questionMap,
        );

        $key = md5($userMessage->content);

        $cached = LlmResponse::where('key', $key)->first(['value']);

        if (isset($cached)) {
            return $cached->value;
        }

        try {
            $response = $this->chat->complete([$systemMessage, $userMessage],
                false,
            );
            $text = $response->getText();

            LlmResponse::create(['key' => $key, 'value' => $text]);

            return $text;
        } catch (Exception $e) {
            Log::error($e);

            return 'Error: No summary retrieved';
        }
    }

    /**
     * Summary of getSummaries
     * @param \User|int|null $user
     * @param callable $addFilter
     * @return array<EvaluationSummary>
     */
    public function getSummaries(User | int | null $user = null, callable $addFilter = null)
    {
        $userId = isset($user) ? self::getUserId($user) : null;
        $q      = FormSubmission::query()->with([
            'submissionPeriod.formSubmissionPeriodSemester.semester',
            'formSubmissionSubject' => [
                'courseSubject',
                'studentSubject',
            ],
        ]);

        if (isset($userId)) {
            $q = $q->whereEvaluateeId($userId);
        }

        if (isset($addFilter)) {
            $q = $addFilter($q);
        }
        /**
         * @var \Illuminate\Database\Eloquent\Collection<FormSubmission>
         */
        $q = $q->get();

        $grouped = $q->groupBy([
            'evaluatee_id',
            function ($data) {
                return $data->submissionPeriod->formSubmissionPeriodSemester->semester_id;
            },
            function ($data) {
                return $data->formSubmissionSubject->course_subject_id;
            },
        ]);

        $summaries = [];

        $usersMap          = [];
        $semestersMap      = [];
        $courseSubjectsMap = [];

        $id = 1;
        foreach ($grouped as $evaluateeId => $byEvaluatee) {
            if (! isset($usersMap[$evaluateeId])) {
                $usersMap[$evaluateeId] = User::whereId($evaluateeId)->first();
            }

            $user = $usersMap[$evaluateeId];

            foreach ($byEvaluatee as $semesterId => $bySemester) {
                if (! isset($semestersMap[$semesterId])) {
                    $semestersMap[$semesterId] = Semester::whereId($semesterId)->first();
                }

                $semester = $semestersMap[$semesterId];

                foreach ($bySemester as $courseSubjectId => $byCourseSubjects) {
                    if (! isset($courseSubjectsMap[$courseSubjectId])) {
                        $courseSubjectsMap[$courseSubjectId] = CourseSubject::whereId($courseSubjectId)->first();
                    }

                    $courseSubject = $courseSubjectsMap[$courseSubjectId];

                    $summaries[] = self::getSummary($id, $user, $semester, $courseSubject, $byCourseSubjects);

                    ++$id;
                }

            }
        }

        return $summaries;
    }

    public function getDetailedEvaluationSummary(User $evaluatee, Semester $semester, ?CourseSubject $courseSubject): DetailedEvaluationSummary
    {
        $q = FormSubmission::query()->with([
            'submissionPeriod'      => ['formSubmissionPeriodSemester.semester'],
            'answers'               => [
                'formQuestion.options',
                'selectedOptions.option',
            ],
            'formSubmissionSubject' => [
                'courseSubject',
                'studentSubject',
            ],
        ]);

        $q = $q->whereEvaluateeId(self::getUserId($evaluatee))
            ->whereHas('submissionPeriod', fn($q) => $q->whereHas('formSubmissionPeriodSemester', fn($q) => $q->whereSemesterId($semester->id)));

        if (isset($courseSubject)) {

            $q = $q->whereHas('formSubmissionSubject', fn($q) => $q->whereCourseSubjectId($courseSubject->id));
        }

        $all = $q->get();

        $summary = self::getSummary(0, $evaluatee, $semester, $courseSubject, $all);

        return new DetailedEvaluationSummary(
            $summary->id,
            $summary->evaluatee,
            $summary->semester,
            $summary->courseSubject,
            $summary->averageRating,
            $summary->totalEvaluations,
            $this->getOverallSummary($evaluatee, $all),
            []
        );
    }
}
