<?php
namespace App\Models\Summaries;

use App\Models\CourseSubject;
use App\Models\Semester;
use App\Models\Summaries\EvaluationSummary;
use App\Models\User;

class DetailedEvaluationSummary extends EvaluationSummary
{
    public function __construct(
        int $id,
        User $evaluatee,
        Semester $semester,
        ?CourseSubject $courseSubject,
        float $averageRating,
        int $totalEvaluations,
        public string $overallSummary,
        public array $questionSummaries,
    ) {
        parent::__construct($id, $evaluatee, $semester, $courseSubject, $averageRating, $totalEvaluations);
    }
}
