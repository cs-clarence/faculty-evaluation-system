<?php
namespace App\Models\Summaries;

use App\Models\CourseSubject;
use App\Models\Semester;
use App\Models\User;

class EvaluationSummary
{

    public function __construct(
        public int $id,
        public User $evaluatee,
        public Semester $semester,
        public ?CourseSubject $courseSubject,
        public float $averageRating,
        public int $totalEvaluations,
    ) {}
}
