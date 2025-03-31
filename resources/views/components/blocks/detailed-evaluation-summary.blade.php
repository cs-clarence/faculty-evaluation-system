@props(['detailedEvaluationSummary'])

<div class="flex flex-col gap-4">
    <div class="flex flex-col gap-1">
        <h4 class="text-xs text-gray-700">OVERALL SUMMARY</h4>
        <p>{{ $detailedEvaluationSummary->overallSummary }}</p>
    </div>
    <div class="flex flex-col gap-1">
        <h4 class="text-xs text-gray-700">AVERAGE RATING</h4>
        <p>{{ $detailedEvaluationSummary->averageRating }}%</p>
    </div>
    <div class="flex flex-col gap-1">
        <h4 class="text-xs text-gray-700">TOTAL EVALUATIONS</h4>
        <p>{{ $detailedEvaluationSummary->totalEvaluations }}</p>
    </div>
    <div class="flex flex-col gap-1">
        <h4 class="text-xs text-gray-700">QUESTION SUMMARIES</h4>
        <p>{{ $detailedEvaluationSummary->totalEvaluations }}</p>
    </div>
</div>
