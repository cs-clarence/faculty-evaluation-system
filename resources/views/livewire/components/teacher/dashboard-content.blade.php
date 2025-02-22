<div class="contents">
    <div class="top flex justify-start mb-4">
        <h2 class="text-2xl font-bold">Evaluations</h2>
    </div>
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $columns = [
                [
                    'label' => 'Subject',
                    'render' => fn() => 'Subject',
                ],
                [
                    'label' => 'Semester',
                    'render' => 'submissionPeriod.semester',
                ],
                [
                    'label' => 'Rating',
                    'render' => fn($i) => $i->rating . '%',
                ],
                [
                    'label' => 'Actions',
                    'render' => 'blade:table.actions',
                    'props' => [
                        'actions' => [
                            'view' => [
                                'order' => 0,
                                'type' => 'link',
                                'label' => 'View',
                                'color' => 'primary',
                                'href' => fn($data) => route('user.form-submissions.form-submission', [
                                    'formSubmission' => $data->id,
                                ]),
                            ],
                            'delete' => [
                                'condition' => false,
                            ],
                            'archive' => [
                                'condition' => false,
                            ],
                            'edit' => [
                                'condition' => false,
                            ],
                        ],
                    ],
                ],
            ];
        @endphp
        <div class="col-span-1 md:col-span-3 overflow-auto">
            <x-table :data="$formSubmissions" :$columns />
        </div>
    </div>
</div>
