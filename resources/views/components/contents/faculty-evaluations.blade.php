@props(['data', 'search' => false, 'searchText' => ''])
@php
    use Illuminate\Support\Facades\Gate;
    use App\Models\FormSubmission;
@endphp

<div class="contents">
    <x-sections.header title="Faculty Evaluations">
        <div class="flex flex-row gap-1 items-center" wire:key="export-actions-{{ $searchText }}">
            @if (isset($searchText) && $searchText !== '')
                <x-button variant="outlined" wire:click="exportExcel" size="sm"
                    wire:confirm="If you have filters set, the export will also be filtered. Continue?">
                    Export Excel
                </x-button>
                <x-button variant="outlined" wire:click="exportCsv" size="sm"
                    wire:confirm="If you have filters set, the export will also be filtered. Continue?">
                    Export CSV
                </x-button>
            @else
                <x-button variant="outlined" wire:click="exportExcel" size="sm">
                    Export Excel
                </x-button>
                <x-button variant="outlined" wire:click="exportCsv" size="sm"
                    wire:confirm="If you have filters set, the export will also be filtered. Continue?">
                    Export CSV
                </x-button>
            @endif
        </div>
    </x-sections.header>
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    [
                        'label' => 'Period',
                        'render' => 'submissionPeriod.name',
                    ],
                    [
                        'label' => 'Evaluator Role',
                        'render' => 'submissionPeriod.evaluatorRole.display_name',
                        'condition' => Gate::allows('viewEvaluatorRole', FormSubmission::class),
                    ],
                    [
                        'label' => 'Evaluator',
                        'render' => 'evaluator.name',
                        'condition' => Gate::allows('viewEvaluator', FormSubmission::class),
                    ],
                    [
                        'label' => 'Teacher',
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
                        'label' => 'Rating',
                        'render' => fn($data) => $data->rating . '%',
                    ],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'mergeDefaultActions' => false,
                            'actions' => [
                                'open' => [
                                    'label' => 'Open',
                                    'color' => 'primary',
                                    'type' => 'link',
                                    'href' => fn($data) => route('view-evaluation', [
                                        'formSubmission' => $data->id,
                                    ]),
                                    'condition' => true,
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp
            <x-table :$data :$columns>
                @if ($search)
                    <x-slot:actions>
                        <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                    </x-slot:actions>
                @endif
            </x-table>
        </div>
    </div>
</div>
