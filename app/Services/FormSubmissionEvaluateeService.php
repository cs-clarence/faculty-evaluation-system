<?php
namespace App\Services;

use App\Models\CourseSubject;
use App\Models\FormSubmissionPeriod;
use App\Models\RoleCode;
use App\Models\User;
use Exception;

class FormSubmissionEvaluateeService
{

    public function getAvailableEvaluateesForSubject(FormSubmissionPeriod $period, User $evaluator, CourseSubject $subject)
    {
        if (! ($period->isEvaluatorRole(RoleCode::Student) && $period->isEvaluateeRole(RoleCode::Teacher))) {
            throw new Exception('Only students can evaluate teachers for a subject');
        }

    }
}
