<?php

use App\Models\CourseSubject;
use App\Models\Semester;

class EvaluationSummary
{

    public function __construct(
        public User $evaluatee,
        public Semester $semester,
        public CourseSubject $courseSubject,
        public float $averageScore,
        public int $totalEvaluations,
    ) {}
}
