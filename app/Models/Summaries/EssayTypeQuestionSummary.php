<?php
namespace App\Models\Summaries;

class EssayTypeQuestionSummary
{
    /**
     * Summary of __construct
     * @param string $question
     * @param array<string> $answers
     * @param string $summary
     */
    public function __construct(
        public string $question,
        public array $answers,
        public string $summary,
    ) {

    }
}
