@props(['formSubmission'])

<x-layouts.pdf title="{{ $formSubmission->submissionPeriod->name }}">
    <x-slot:head>
        <style>
            .layout {
                width: 100%;
                margin-bottom: 32px;
                font-family: sans-serif;
            }

            td,
            th {
                text-align: left;
            }
        </style>
    </x-slot:head>

    <table class="layout">
        <thead>
            <th>
                Evaluatee ({{ $formSubmission->evaluatee->role->display_name }})
            </th>
            <th>
                Form
            </th>
            <th>
                Submission Period
            </th>
            @isset($formSubmission->submissionPeriod->semester)
                <th>
                    Semester
                </th>
            @endisset
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $formSubmission->evaluatee->name }}
                </td>
                <td>
                    {{ $formSubmission->submissionPeriod->form->name }}
                </td>
                <td>
                    {{ $formSubmission->submissionPeriod->name }}
                </td>
                <td>
                    {{ $formSubmission->submissionPeriod->semester }}
                </td>
            </tr>
        </tbody>
    </table>

    <x-form-submission-summary.table :$formSubmission showInterpretation showReason showText pdf />
</x-layouts.pdf>
