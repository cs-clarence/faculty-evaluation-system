<div class="contents">
    <x-sections.header title="Form Submissions" />
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Period', 'render' => 'submissionPeriod.name'],
                    ['label' => 'Evaluator Role', 'render' => 'evaluator.role.display_name'],
                    ['label' => 'Evaluator', 'render' => 'evaluator.name'],
                    ['label' => 'Evaluatee Role', 'render' => 'evaluatee.role.display_name'],
                    ['label' => 'Evaluatee', 'render' => 'evaluatee.name'],
                    [
                        'label' => 'Subject',
                        'render' => fn($data) => isset($data->subject) ? $data->subject->name : 'N/A',
                    ],
                    [
                        'label' => 'Department',
                        'render' => fn($data) => isset($data->department) ? $data->department->code : 'N/A',
                    ],
                    ['label' => 'Semester', 'render' => 'submissionPeriod.semester'],
                    ['label' => 'Rating', 'render' => fn($value) => $value->rating . '%'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'mergeDefaultActions' => true,
                            'actions' => [
                                'edit' => [
                                    'condition' => false,
                                ],
                                'archive' => [
                                    'condition' => false,
                                ],
                                'unarchive' => [
                                    'condition' => false,
                                ],
                                'view' => [
                                    'order' => 0,
                                    'type' => 'link',
                                    'label' => 'View',
                                    'color' => 'primary',
                                    'href' => fn($data) => route('admin.form-submissions.form-submission', [
                                        'formSubmission' => $data->id,
                                    ]),
                                ],
                                'delete' => [
                                    'condition' => auth()->user()->isAdmin(),
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp
            <x-table :data="$formSubmissions" :$columns>
                <x-slot:actions>
                    <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                </x-slot:actions>
            </x-table>
        </div>
    </div>
</div>
