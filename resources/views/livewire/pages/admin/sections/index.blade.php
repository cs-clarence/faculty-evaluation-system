@php
    use Illuminate\Support\Number;
@endphp

<div class="contents">
    <x-sections.header title="Sections">
        <x-button id="addCourseBtn" wire:click='openForm'>
            Add Section
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Year Level', 'render' => fn($data) => Number::ordinal($data->year_level)],
                    ['label' => 'Semester', 'render' => fn($data) => Number::ordinal($data->semester)],
                    ['label' => 'Section Name', 'render' => 'name'],
                    ['label' => 'Section Code', 'render' => 'code'],
                    ['label' => 'Course', 'render' => 'course.name'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [
                                'edit' => [
                                    'condition' => fn($data) => !$data->hasDependents(),
                                ],
                            ],
                        ],
                    ],
                ];
                $paginate = [
                    'perPage' => 15,
                ];
            @endphp
            <x-table :data="$sections" :$columns :$paginate>
                <x-slot:actions>
                    <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                </x-slot:actions>
            </x-table>
        </div>
    </div>

    @if ($isFormOpen)
        <x-modal-scrim />
        <x-dialog.container wire:click.self='closeForm'>
            <x-dialog wire:key="section-form" el="form" wire:submit.prevent="save">
                <x-dialog.title>
                    @isset($model)
                        Edit Section
                    @else
                        Add New Section
                    @endisset
                </x-dialog.title>
                <x-dialog.content>
                    @csrf
                    <input type="hidden" name="id" wire:model.blur="form.id">
                    <x-form-control>
                        <x-form-control.label key="form.year_level">Year Level</x-form-control.label>
                        <x-input key="form.year_level" required wire:model.live="form.year_level" />
                        <x-form-control.error-text key="form.year_level" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.semester">Semester</x-form-control.label>
                        <x-input key="form.semester" required wire:model.live="form.semester" />
                        <x-form-control.error-text key="form.semester" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.name">Name</x-form-control.label>
                        <x-input key="form.name" required wire:model.live="form.name" />
                        <x-form-control.error-text key="form.name" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.course_id">Course</x-form-control.label>
                        <x-select key="form.course_id" required :options="$courses" :label="fn($i) => $i->name" :value="fn($i) => $i->id"
                            wire:model.live="form.course_id" empty="No Courses Available" placeholder="Select course" />
                        <x-form-control.error-text key="form.course_id" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.code">Section Code</x-form-control.label>
                        <x-input key="form.code" required wire:model="form.code" />
                        <x-form-control.error-text key="form.code" />
                    </x-form-control>
                </x-dialog.content>
                <x-dialog.actions>
                    <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                        variant="text">Cancel</x-button>
                    <x-button type="submit">Save</x-button>
                </x-dialog.actions>
            </x-dialog>
        </x-dialog.container>
    @endif
</div>
