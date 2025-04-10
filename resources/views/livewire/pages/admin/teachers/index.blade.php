@php
    use App\Models\RoleCode;
@endphp
<div class="contents">
    <x-layouts.loader>

        <x-sections.header title="Teachers">
            <x-button id="addSubjectBtn" wire:click='openForm'>
                Add Teacher
            </x-button>
        </x-sections.header>

        <!-- Main Dashboard Content -->
        <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6 flex-grow">
            <!-- Total Users and Completed Tasks -->

            <!-- Responsive Table -->
            <div class="col-span-1 md:col-span-3 overflow-auto">
                @php
                    $columns = [
                        ['label' => 'Name', 'render' => 'name'],
                        ['label' => 'Email', 'render' => 'email'],
                        ['label' => 'Department', 'render' => 'teacher.department.name'],
                        ['label' => 'Semesters', 'render' => 'teacher.teacher_semesters_count'],
                        ['label' => 'Subjects', 'render' => 'teacher.teacher_subjects_count'],
                        [
                            'label' => 'Actions',
                            'render' => 'blade:table.actions',
                            'props' => [
                                'actions' => [
                                    'View' => [
                                        'order' => 1.1,
                                        'label' => 'View',
                                        'type' => 'link',
                                        'href' => fn($data) => route('admin.teachers.teacher', [
                                            'teacher' => $data->teacher->id,
                                        ]),
                                    ],
                                    'edit_password' => [
                                        'order' => 1.2,
                                        'label' => 'Edit Password',
                                        'color' => 'primary',
                                        'wire:click' => fn($data) => "editPassword({$data->id})",
                                    ],
                                ],
                            ],
                        ],
                    ];
                @endphp

                <x-table :data="$teachers" :$columns>
                    <x-slot:actions>
                        <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                        <x-combobox key="filter_department_id" wire:model.live="filter_department_id" :options="$departmentFilters ?? []"
                            :value="fn($i) => $i->id" :label="fn($i) => $i->name" placeholder="Filter by Department" class="min-w-80"
                            placeholder="Filter by Department" empty="No Departments Available" />

                        <x-button wire:click="resetFilters" size="sm" variant="outlined" color="neutral">
                            Reset
                        </x-button>
                    </x-slot:actions>
                </x-table>
            </div>
        </div>

        @if ($isFormOpen)
            <x-modal-scrim />
            <x-dialog.container wire:click.self='closeForm'>
                <x-dialog el="form" wire:submit.prevent="save" wire:key="teacher-form">
                    <x-dialog.title>
                        @isset($model)
                            Edit Teacher
                        @else
                            Add New Teacher
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content>
                        <x-forms.user-form-fields :departments="$departments">
                            @if ($form->include_base)
                                <input type="hidden" name="role_code" value="{{ RoleCode::Teacher->value }}" />
                                <x-slot:id name="form.id" wire:model="form.id"></x-slot:id>
                                <x-slot:name name="form.name" wire:model="form.name"></x-slot:name>
                                <x-slot:email name="form.email" wire:model="form.email"></x-slot:email>
                                <x-slot:departmentId name="form.department_id" wire:model="form.department_id"></x-slot:departmentId>
                            @endif
                            @if ($form->include_password)
                                <x-slot:password name="form.password" wire:model="form.password"></x-slot:password>
                                <x-slot:passwordConfirmation name="form.password_confirmation"
                                    wire:model="form.password_confirmation">
                                </x-slot:passwordConfirmation>
                            @endif
                            <div class="flex justify-end gap-1">
                            </div>
                        </x-forms.user-form-fields>
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif
    </x-layouts.loader>
</div>
