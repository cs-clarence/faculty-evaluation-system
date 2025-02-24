@props([
    'formSubmission',
    'showText' => false,
    'showInterpretation' => false,
    'showReason' => false,
    'createQuestionLink' => null,
    'pdf' => false,
])

@if ($pdf)
    @push('styles')
        <x-styles.pdf />
    @endpush
@endif


@php
    $tdClass = !$pdf ? 'p-2 border border-gray-500' : 'pdf-td';
    $thClass = !$pdf ? '' . ' ' . $tdClass : 'pdf-th';
    $trClass = !$pdf ? '' : 'pdf-tr';
    $tableClass = !$pdf ? 'table-fixed' : 'pdf-table';
    $totalThClass = !$pdf ? 'text-right' : 'pdf-total-th';
@endphp

@if ($pdf)
    <div class="pdf">
@endif

<table class="{{ $tableClass }}">
    <thead>
        <tr class="{{ $trClass }}">
            <th class="{{ $thClass }}" rowspan="2">Question</th>
            @if ($showText)
                <th class="{{ $thClass }}" rowspan="2">Answer</th>
            @endif
            @if ($showInterpretation)
                <th class="{{ $thClass }}" rowspan="2">Interpretation</th>
            @endif
            <th class="{{ $thClass }}" colspan="2">Score</th>
            <th class="{{ $thClass }}" colspan="2">Weighted Score</th>
            @if ($showReason)
                <th class="{{ $thClass }}" rowspan="2">Reason</th>
            @endif
        </tr>
        <tr class="{{ $trClass }}">
            <th class="{{ $thClass }}">Value</th>
            <th class="{{ $thClass }}">Out Of</th>
            <th class="{{ $thClass }}">Value</th>
            <th class="{{ $thClass }}">Out Of</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($formSubmission->summary as $breakdown)
            <tr class="{{ $trClass }}">
                <td class="{{ $tdClass }} max-w-[400px]">
                    @if (isset($createQuestionLink))
                        <a href="{{ $createQuestionLink($breakdown) }}" class="transition-colors hover:text-blue-400">
                            {{ $breakdown->question }}
                        </a>
                    @else
                        {{ $breakdown->question }}
                    @endif
                </td>
                @if ($showText)
                    <td class="{{ $tdClass }} max-w-[300px]">{{ $breakdown->text }}</td>
                @endif
                @if ($showInterpretation)
                    <td class="{{ $tdClass }} max-w-[400px]">{{ $breakdown->interpretation }}</td>
                @endif
                <td class="{{ $tdClass }}">{{ $breakdown->value }}</td>
                <td class="{{ $tdClass }}">{{ $breakdown->max_value }}</td>
                <td class="{{ $tdClass }}">{{ $breakdown->weighted_value }}</td>
                <td class="{{ $tdClass }}">{{ $breakdown->max_weighted_value }}</td>
                @if ($showReason)
                    <td class="{{ $tdClass }} max-w-[400px]">{{ $breakdown->reason }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="{{ $trClass }}">
            <th class="{{ $thClass }} {{ $totalThClass }}"
                colspan="{{ 1 + ($showText ? 1 : 0) + ($showInterpretation ? 1 : 0) }}">
                Total
            </th>
            <th class="{{ $tdClass }}">
                {{ $formSubmission->total_value }}
            </th>
            <th class="{{ $tdClass }}">
                {{ $formSubmission->form->total_max_value }}
            </th>
            <th class="{{ $tdClass }}">
                {{ $formSubmission->rating }}%
            </th>
            <th class="{{ $tdClass }}">
                100%
            </th>
            @if ($showReason)
                <th class="{{ $tdClass }}"></th>
            @endif
        </tr>
    </tfoot>
</table>

@if ($pdf)
    </div>
@endif
