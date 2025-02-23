<?php
namespace App\Models;

class PendingEvaluation
{
    public int $id = 0;
    public function __construct(
        public FormSubmissionPeriod $submissionPeriod,
        public ?StudentSubject $studentSubject = null,
        public ?CourseSubject $courseSubject = null,
        public ?User $evaluatee = null,
    ) {
    }
}
