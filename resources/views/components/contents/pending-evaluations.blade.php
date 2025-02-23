@props(['data'])

<div class="contents">
    <x-sections.header title="Pending Evaluations" />
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    [
                        'label' => 'Period',
                        'render' => 'submissionPeriod.name',
                    ],
                    [
                        'label' => 'Closes At',
                        'render' => 'submissionPeriod.ends_at',
                    ],
                    ['label' => 'Evaluatee Role', 'render' => 'submissionPeriod.evaluateeRole.display_name'],
                    [
                        'label' => 'Evaluatee',
                        'render' => fn($data) => isset($data->evaluatee) ? $data->evaluatee->name : 'None Selected',
                    ],
                    [
                        'label' => 'Subject',
                        'render' => fn($data) => isset($data->studentSubject)
                            ? $data->studentSubject->subject->name
                            : (isset($data->courseSubject)
                                ? $data->courseSubject->subject->name
                                : 'None'),
                    ],
                    [
                        'label' => 'Semester',
                        'render' => fn($data) => isset($data->submissionPeriod->semester)
                            ? $data->submissionPeriod->semester
                            : 'None',
                    ],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [
                                'open' => [
                                    'label' => 'Open',
                                    'color' => 'primary',
                                    'type' => 'link',
                                    'href' => fn($data) => route('user.submit-evaluation', [
                                        'formSubmissionPeriod' => $data->submissionPeriod->id,
                                        'evaluateeId' => $data->evaluatee?->id,
                                        'courseSubjectId' =>
                                            $data->courseSubject?->id ?? $data->studentSubject?->course_subject_id,
                                        'studentSubjectId' => $data->studentSubject?->id,
                                    ]),
                                    'condition' => true,
                                ],
                                'edit' => [
                                    'condition' => false,
                                ],
                                'archive' => [
                                    'condition' => false,
                                ],
                                'unarchive' => [
                                    'condition' => false,
                                ],
                                'delete' => [
                                    'condition' => false,
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp
            <x-table :$data :$columns>
            </x-table>
        </div>
    </div>
</div>
