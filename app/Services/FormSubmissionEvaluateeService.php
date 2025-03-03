<?php
namespace App\Services;

use App\Models\CourseSubject;
use App\Models\FormSubmissionPeriod;
use App\Models\RoleCode;
use App\Models\User;

class FormSubmissionEvaluateeService
{
    private static function getCourseSubject(CourseSubject | int $courseSubject)
    {
        return $courseSubject instanceof CourseSubject ? $courseSubject : CourseSubject::whereId($courseSubject)->first();
    }

    private static function getDepartmentId(CourseSubject | int $courseSubject)
    {
        $cs = self::getCourseSubject($courseSubject);

        return $cs->courseSemester->course->department->id;
    }

    public function getAvailableEvaluatees(
        FormSubmissionPeriod $period,
        User $evaluator,
        CourseSubject | int | null $courseSubject = null
    ) {
        $q = User::query();
        if (isset($courseSubject)
            && $period->isEvaluatorRole(RoleCode::Student)
            && $period->isEvaluateeRole(RoleCode::Teacher)
        ) {
            $deptId = self::getDepartmentId($courseSubject);

            $q = $q
                ->whereHas('teacher', fn($q) => $q->whereDepartmentId($deptId));
        }

        return $q->whereRoleId($period->evaluatee_role_id)
            ->whereNot('id', $evaluator->id);
    }
}
