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
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900/50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($model)
                    <h3 class="text-lg font-semibold mb-4">Edit Submission Period</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Submission Period</h3>
                @endisset

                <!-- Add Subject Form -->
                <form wire:submit.prevent="save">
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <x-form-control>
                        <x-form-control-label for="name">Name</x-form-control-label>
                        <x-input type="text" name="name" id="name" required wire:model="form.name" />
                        @error('form.name')
                            <x-form-control-error-text>
                                {{ $message }}
                            </x-form-control-error-text>
                        @enderror
                    </x-form-control>
                    <x-form-control>
                        <x-form-control-label for="form.evaluator_role_id">Evaluator</x-form-control-label>
                        <x-select key="form.evaluator_role_id" required :options="$evaluatorRoles" :label="fn($i) => $i->display_name"
                            :value="fn($i) => $i->id" placeholder="Select evaluator" empty="No Evaluators Available"
                            wire:model.change="form.evaluator_role_id" />
                        @error('form.evaluator_role_id')
                            <x-form-control-error-text>{{ $message }}</x-form-control-error-text>
                        @enderror
                    </x-form-control>
                    <x-form-control>
                        <x-form-control-label for="form.evaluatee_role_id">Evaluatee</x-form-control-label>
                        <x-select key="form.evaluatee_role_id" required :options="$evaluateeRoles" :label="fn($i) => $i->display_name"
                            :value="fn($i) => $i->id" placeholder="Select evaluatee" empty="No Evaluatees Available"
                            wire:model.change="form.evaluatee_role_id" />
                        @error('form.evaluatee_role_id')
                            <x-form-control-error-text>{{ $message }}</x-form-control-error-text>
                        @enderror
                    </x-form-control>
                    @if ($showSemester)
                        <x-form-control class="mb-4">
                            <x-form-control-label for="ends_at"
                                class="block text-gray-700">Semester</x-form-control-label>
                            <x-select name="ends_at" id="semester_id" required wire:model="form.semester_id"
                                :options="$semesters" :label="fn($i) => $i->__tostring()" :value="fn($i) => $i->id" placeholder="Select semester"
                                empty="No Semesters Available" />
                            @error('form.semester_id')
                                <x-form-control-error-text>{{ $message }}</x-form-control-error-text>
                            @enderror
                        </x-form-control>
                    @endif
                    <x-form-control class="mb-4">
                        <x-form-control-label for="starts_at">Start Date</x-form-control-label>
                        <x-input type="datetime-local" name="starts_at" id="starts_at" required
                            wire:model="form.starts_at" />
                        @error('form.starts_at')
                            <x-form-control-error-text>{{ $message }}</x-form-control-error-text>
                        @enderror
                    </x-form-control>
                    <x-form-control class="mb-4">
                        <x-form-control-label for="ends_at">End Date</x-form-control-label>
                        <x-input type="datetime-local" name="ends_at" id="starts_at" required
                            wire:model="form.ends_at" />
                        @error('form.ends_at')
                            <x-form-control-error-text>{{ $message }}</x-form-control-error-text>
                        @enderror
                    </x-form-control>
                    <x-form-control class="mb-4">
                        <x-form-control-label for="form_id">Form</x-form-control-label>
                        <x-select name="form_id" id="form_id" required :options="$forms" :label="fn($i) => $i->name"
                            :value="fn($i) => $i->id" placeholder="Select form" empty="No Forms Available"
                            wire:model="form.form_id" />
                        @error('form.form_id')
                            <x-form-control-error-text>{{ $message }}</x-form-control-error-text>
                        @enderror
                    </x-form-control>
                    <div class="mb-4 flex flex-row items-center gap-2">
                        <input type="checkbox" name="is_open" id="is_open" wire:model="form.is_open">
                        <label for="is_open" class="block text-sm font-medium text-gray-700">Open</label>
                        @error('form.is_open')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4 flex flex-row items-center gap-2">
                        <input type="checkbox" name="is_submissions_editable" id="is_submissions_editable"
                            wire:model="form.is_submissions_editable">
                        <label for="is_submissions_editable" class="block text-sm font-medium text-gray-700">Submissions
                            Editable</label>
                        @error('form.is_submissions_editable')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-1">
                        <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
