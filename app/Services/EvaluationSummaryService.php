<?php
namespace App\Services;

use App\Models\FormSubmission;
use User;

class EvaluationSummaryService
{
    private static function getUserId(User | int $user): int
    {
        return $user instanceof User ? $user->id : $user;
    }

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

        foreach ($grouped as $byEvaluatee) {
            foreach ($byEvaluatee as $bySemester) {
                foreach ($bySemester as $data) {
                }
            }
        }

        return $summaries;
    }
}
