@props(['data', 'searchText' => '', 'search' => false])

@php
    use App\Models\RoleCode;
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="contents">

    <x-sections.header title="Evaluations Received" />
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    [
                        'label' => 'Period',
                        'render' => 'submissionPeriod.name',
                    ],
                    [
                        'label' => 'Subject',
                        'render' => fn($data) => isset($data->subject) ? $data->subject->name : 'N/A',
                    ],
                    [
                        'label' => 'Semester',
                        'render' => fn($data) => isset($data->submissionPeriod->semester)
                            ? $data->submissionPeriod->semester
                            : 'N/A',
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
                                'view' => [
                                    'label' => 'View',
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
