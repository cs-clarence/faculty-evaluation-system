<?php
namespace App\Services;

use App\Facades\Services\StudentService;
use App\Models\FormSubmissionPeriod;
use App\Models\PendingEvaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PendingEvaluationsService
{

    /**
     * Summary of getPendingEvaluations
     * @return array<PendingEvaluation>
     */
    public function getPendingEvaluations(User $user): array
    {

        $pendingEvalutuations = [];

        $activePeriods = FormSubmissionPeriod::evaluator($user->role->code)
            ->with(['formSubmissionPeriodSemester.semester.schoolYear'])
            ->isOpen()
            ->get();

        $activePeriodSemesterIds = $activePeriods
            ->map(fn($i) => $i->formSubmissionPeriodSemester?->semester_id)
            ->filter(fn($d) => isset($d));

        $id = 1;

        if ($user->isStudent()) {
            $student = StudentService::currentStudent()->with([
                'studentSubjects' => fn(HasManyThrough $studentSubjects) => $studentSubjects
                    ->with([
                        'courseSubject' => [
                            'subject',
                            'courseSemester.course.department',
                        ],
                        'studentSemester',
                    ])
                    ->whereIn('semester_id', $activePeriodSemesterIds)
                    ->doesntHave('formSubmissionSubject'),
                'user',
            ])->first();

            foreach ($activePeriods as $p) {
                $studentSubjects = $student->studentSubjects->filter(fn($ss) =>
                    $ss->studentSemester->semester_id === $p->formSubmissionPeriodSemester->semester_id
                );
                if (count($studentSubjects) <= 0) {
                    continue;
                }
                foreach ($studentSubjects as $s) {
                    $pe = new PendingEvaluation(
                        $p,
                        $s,
                        $s->courseSubject,
                        $s->teacher?->user,
                    );

                    $pe->id = $id++;

                    $pendingEvalutuations[] = $pe;
                }
            }
        }

        if ($user->isTeacher() || $user->isDean()) {
            $deptId = $user->department_id;
            foreach ($activePeriods as $p) {
                $teacherUsers = User::roleTeacher()
                    ->whereNot('id', $user->id)
                    ->whereHas('teacher',
                        fn($t) => $t->whereDepartmentId($deptId))
                    ->whereDoesntHave('evaluationsReceived', fn($q) => $q->whereFormSubmissionPeriodId($p->id))
                    ->get();
                foreach ($teacherUsers as $u) {
                    $pendingEvalutuations[] = new PendingEvaluation(
                        $p,
                        evaluatee: $u
                    );
                }
            }
        }

        if ($user->isHumanResourcesStaff()) {
            foreach ($activePeriods as $p) {
                $teacherUsers = User::roleTeacher()
                    ->whereNot('id', $user->id)
                    ->has('teacher')
                    ->whereDoesntHave('evaluationsReceived', fn($q) => $q->whereFormSubmissionPeriodId($p->id))
                    ->get();
                foreach ($teacherUsers as $u) {
                    $pendingEvalutuations[] = new PendingEvaluation(
                        $p,
                        evaluatee: $u
                    );
                }
            }
        }

        return $pendingEvalutuations;
    }
}
