<div class="contents">
    <x-sections.header title="Form Submissions" />
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Evaluator Role', 'render' => 'evaluator.role.display_name'],
                    ['label' => 'Evaluator', 'render' => 'evaluator.name'],
                    ['label' => 'Evaluatee Role', 'render' => 'evaluatee.role.display_name'],
                    ['label' => 'Evaluatee', 'render' => 'evaluatee.name'],
                    ['label' => 'Rating', 'render' => fn($value) => $value->rating . '%'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'mergeDefaultActions' => false,
                            'actions' => [
                                'view' => [
                                    'order' => 0,
                                    'type' => 'link',
                                    'label' => 'View',
                                    'color' => 'primary',
                                    'href' => fn($data) => route('admin.form-submissions.form-submission', [
                                        'formSubmission' => $data->id,
                                    ]),
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
