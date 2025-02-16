<div class="contents">
    <x-sections.header title="Submission Periods">
        <x-button wire:click='openForm'>
            Add Submission Period
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Name', 'render' => 'name'],
                    ['label' => 'Form', 'render' => 'form.name'],
                    ['label' => 'Evaluator', 'render' => 'evaluatorRole'],
                    ['label' => 'Evaluatee', 'render' => 'evaluateeRole'],
                    ['label' => 'Semester', 'render' => fn($data) => isset($data->semester) ? $data->semester : 'None'],
                    ['label' => 'Start Date', 'render' => 'starts_at'],
                    ['label' => 'End Date', 'render' => 'ends_at'],
                    ['label' => 'Open', 'render' => fn($data) => $data->is_open ? 'Yes' : 'No'],
                    [
                        'label' => 'Submissions Editable',
                        'render' => fn($data) => $data->is_submissions_editable ? 'Yes' : 'No',
                    ],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [
                                'open' => [
                                    'order' => 1.1,
                                    'label' => 'Open',
                                    'color' => 'primary',
                                    'wire:click' => fn($data) => "open({$data->id})",
                                    'condition' => fn($data) => !$data->is_open,
                                ],
                                'close' => [
                                    'order' => 1.2,
                                    'label' => 'Close',
                                    'color' => 'danger',
                                    'wire:click' => fn($data) => "close({$data->id})",
                                    'condition' => fn($data) => $data->is_open,
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp

            <x-table :data="$formSubmissionPeriods" :$columns />
        </div>
    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <x-modal-scrim />
        <x-dialog.container wire:click.self="closeForm">
            <x-dialog el="form" wire:submit.prevent="save">
                <x-dialog.title>
                    @isset($model)
                        Edit Submission Period
                    @else
                        Add New Submission Period
                    @endisset
                </x-dialog.title>
                <x-dialog.content>
                    @csrf
                    <x-input type="hidden" name="id" wire:model.defer="form.id" />
                    <x-form-control>
                        <x-form-control.label key="name">Name</x-form-control.label>
                        <x-input type="text" name="name" id="name" required wire:model="form.name" />
                        <x-form-control.error-text key="form.name" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label for="form.evaluator_role_id">Evaluator</x-form-control.label>
                        <x-select key="form.evaluator_role_id" required :options="$evaluatorRoles" :label="fn($i) => $i->display_name"
                            :value="fn($i) => $i->id" placeholder="Select evaluator" empty="No Evaluators Available"
                            wire:model.change="form.evaluator_role_id" />
                        <x-form-control.error-text key="form.evaluator_role_id" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.evaluatee_role_id">Evaluatee</x-form-control.label>
                        <x-select key="form.evaluatee_role_id" required :options="$evaluateeRoles" :label="fn($i) => $i->display_name"
                            :value="fn($i) => $i->id" placeholder="Select evaluatee" empty="No Evaluatees Available"
                            wire:model.change="form.evaluatee_role_id" />
                        <x-form-control.error-text key="form.evaluatee_role_id" />
                    </x-form-control>
                    @if ($showSemester)
                        <x-form-control>
                            <x-form-control.label key="ends_at"
                                class="block text-gray-700">Semester</x-form-control.label>
                            <x-select name="ends_at" id="semester_id" required wire:model="form.semester_id"
                                :options="$semesters" :label="fn($i) => $i->__tostring()" :value="fn($i) => $i->id" placeholder="Select semester"
                                empty="No Semesters Available" />
                            <x-form-control.error-text key="form.semester_id" />
                        </x-form-control>
                    @endif
                    <x-form-control>
                        <x-form-control.label key="starts_at">Start Date</x-form-control.label>
                        <x-input type="datetime-local" name="starts_at" id="starts_at" required
                            wire:model="form.starts_at" />
                        <x-form-control.error-text key="form.starts_at" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.ends_at">End Date</x-form-control.label>
                        <x-input type="datetime-local" key="form.ends_at" required wire:model="form.ends_at" />
                        <x-form-control.error-text key="form.ends_at" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form_id">Form</x-form-control.label>
                        <x-select name="form_id" id="form_id" required :options="$forms" :label="fn($i) => $i->name"
                            :value="fn($i) => $i->id" placeholder="Select form" empty="No Forms Available"
                            wire:model="form.form_id" />
                        <x-form-control.error-text key="form.form_id" />
                    </x-form-control>
                    <x-form-control flex="row" class="items-center gap-2">
                        <input type="checkbox" id="form.is_open" name="form.is_open" wire:model="form.is_open" />
                        <x-form-control.label key="form.is_open"
                            class="block text-sm font-medium text-gray-700">Open</x-form-control.label>
                        <x-form-control.error-text key="form.is_open" />
                    </x-form-control>
                    <x-form-control flex="row" class="items-center gap-2">
                        <input type="checkbox" id="form.is_submissions_editable" name="form.is_submissions_editable"
                            wire:model="form.is_submissions_editable" />
                        <x-form-control.label key="form.is_submissions_editable">Submissions
                            Editable</x-form-control.label>
                        <x-form-control.error-text key="form.is_submissions_editable" />
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
