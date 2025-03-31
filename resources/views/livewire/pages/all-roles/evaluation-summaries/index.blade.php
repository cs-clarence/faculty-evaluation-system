@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="contents">
    <x-layouts.loader>
        <x-sections.header title="Evaluation Summaries">
        </x-sections.header>

        <!-- Main Dashboard Content -->
        <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6 flex-grow">
            <!-- Total Users and Completed Tasks -->

            <!-- Responsive Table -->
            <div class="col-span-1 md:col-span-3 overflow-auto">
                @php
                    $columns = [];

                    $user = Auth::user();
                    if (!$user->isTeacher()) {
                        $columns[] = [
                            'label' => 'Evaluatee',
                            'render' => 'evaluatee.name',
                        ];
                    }

                    $columns = [
                        ...$columns,
                        ...[
                            [
                                'label' => 'Semester',
                                'render' => fn($data) => $data->semester,
                            ],
                            [
                                'label' => 'Subject',
                                'render' => fn($data) => $data->courseSubject?->subject ?? 'N/A',
                            ],
                            [
                                'label' => 'Average Rating',
                                'render' => fn($data) => $data->averageRating . '%',
                            ],
                            [
                                'label' => 'Total Evaluations',
                                'render' => 'totalEvaluations',
                            ],
                            [
                                'label' => 'Actions',
                                'render' => 'blade:table.actions',
                                'props' => [
                                    'mergeDefaultActions' => false,
                                    'actions' => [
                                        'open' => [
                                            'label' => 'View Details',
                                            'color' => 'primary',
                                            'wire:click' => fn(
                                                $data,
                                            ) => "viewDetailedEvaluationSummary({$data->evaluatee->id}, {$data->semester->id}, {$data->courseSubject?->id})",
                                            'condition' => true,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ];
                @endphp

                <x-table :data="$data" :columns="$columns">
                    <x-slot:actions>
                        @if ($user->isAdmin())
                            <x-combobox key="filter_by_evaluatee_id" wire:model.live="filter_by_evaluatee_id"
                                :options="$evaluatees ?? []" :value="fn($i) => $i->id" :label="fn($i) => $i->name" placeholder="Filter By Evaluatee"
                                class="min-w-80" placeholder="Filter by Evaluatee" empty="No Evaluatee Available" />
                        @endif
                        <x-combobox key="filter_by_semester_id" wire:model.live="filter_by_semester_id"
                            :options="$semesters ?? []" :value="fn($i) => $i->id" :label="fn($i) => $i" placeholder="Filter By Semester"
                            class="min-w-80" placeholder="Filter by Semester" empty="No Semester Available" />

                        <x-combobox key="filter_by_course_subject_id" wire:model.live="filter_by_course_subject_id"
                            :options="$courseSubjects ?? []" :value="fn($i) => $i->id" :label="fn($i) => $i"
                            placeholder="Filter By Course Subject" class="min-w-120"
                            placeholder="Filter by Course Subject" empty="No Course Subject Available" />


                        <x-button wire:click="resetFilters" size="sm" variant="outlined" color="neutral">
                            Reset
                        </x-button>
                    </x-slot:actions>
                </x-table>
            </div>
        </div>

        @isset($detailedEvaluationSummary)
            <x-modal-scrim />
            <x-dialog.container wire:click="closeDetailedEvaluationSummary">
                <x-dialog class="w-120">
                    <x-dialog.title>
                        Evaluation Summary Details
                    </x-dialog.title>
                    <x-dialog.content>
                        <x-blocks.detailed-evaluation-summary :$detailedEvaluationSummary />
                    </x-dialog.content>
                    <x-dialog.actions> <x-button color="neutral" variant="text" type="button"
                            wire:click="closeDetailedEvaluationSummary">Close</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endisset

    </x-layouts.loader>
</div>
