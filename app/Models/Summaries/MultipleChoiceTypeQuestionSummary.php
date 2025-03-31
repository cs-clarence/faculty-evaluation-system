<?php
namespace App\Models\Summaries;

class MultipleChoiceTypeQuestionSummary
{
    /**
     * Summary of __construct
     * @param string $question
     * @param array<string, int> $optionsTally
     * @param string $summary
     */
    public function __construct(
        public string $question,
        public array $optionsTally,
        public string $summary,
    ) {

    }
}
