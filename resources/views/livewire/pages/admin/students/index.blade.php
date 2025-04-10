@php
    use App\Models\RoleCode;
@endphp
<div class="contents" x-data="{ showImportDialog: false, toggleImportDialog() { this.showImportDialog = !this.showImportDialog; } }">
    <x-layouts.loader>

        <x-sections.header title="Students">
            <div class="flex flex-row gap-1">
                <x-button id="addSubjectBtn" wire:click='openForm'>
                    Add Student
                </x-button>
                <x-button variant="outlined" x-on:click="toggleImportDialog()">
                    <x-icon>upload</x-icon>
                    Import
                </x-button>
                <x-button variant="outlined" wire:click="downloadImportTemplate">
                    <x-icon>Download</x-icon>
                    Download Template
                </x-button>
            </div>
        </x-sections.header>

        <!-- Main Dashboard Content -->
        <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6 flex-grow">
            <!-- Total Users and Completed Tasks -->

            <!-- Responsive Table -->
            <div class="col-span-1 md:col-span-3 overflow-auto">
                @php
                    $columns = [
                        ['label' => 'Student Number', 'render' => 'student.student_number'],
                        ['label' => 'Name', 'render' => 'name'],
                        ['label' => 'Email', 'render' => 'email'],
                        ['label' => 'Course', 'render' => 'student.course.name'],
                        ['label' => 'Batch School Year', 'render' => 'student.schoolYear'],
                        ['label' => 'Semesters', 'render' => 'student.student_semesters_count'],
                        ['label' => 'Subjects', 'render' => 'student.student_subjects_count'],
                        [
                            'label' => 'Actions',
                            'render' => 'blade:table.actions',
                            'props' => [
                                'actions' => [
                                    'view' => [
                                        'order' => 1.1,
                                        'label' => 'View',
                                        'type' => 'link',
                                        'color' => 'primary',
                                        'href' => fn($data) => route('admin.students.student', [
                                            'student' => $data->student->id,
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
                <x-table :data="$students" :columns="$columns">
                    <x-slot:actions>
                        <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                        <x-combobox key="filter_department_id" wire:model.live="filter_department_id" :options="$departmentFilters ?? []"
                            :value="fn($i) => $i->id" :label="fn($i) => $i->name" placeholder="Filter by Department" class="min-w-80"
                            placeholder="Filter by Department" empty="No Departments Available" />
                        <x-combobox key="filter_course_id" wire:model.live="filter_course_id" :options="$courseFilters ?? []"
                            class="min-w-80" :value="fn($i) => $i->id" :label="fn($i) => $i->name" placeholder="Filter by Course"
                            empty="No Courses Available" />

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
                <x-dialog el="form" wire:submit.prevent="save" wire:key='student-form'>
                    <x-dialog.title>
                        @isset($model)
                            Edit Student
                        @else
                            Add New Student
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content>
                        <x-forms.user-form-fields :courses="$courses" :schoolYears="$schoolYears">
                            @if ($form->include_base)
                                <input type="hidden" name="role_code" value="{{ RoleCode::Student->value }}"
                                    wire:model="form.role_code">

                                <x-slot:id name="form.id" wire:model="form.id"></x-slot:id>
                                <x-slot:name name="form.name" wire:model="form.name"></x-slot:name>
                                <x-slot:email name="form.email" wire:model="form.email"></x-slot:email>
                                <x-slot:studentNumber name="form.student_number" wire:model="form.student_number"></x-slot:studentNumber>
                                <x-slot:courseId name="form.course_id" wire:model.live="form.course_id"></x-slot:courseId>
                                <x-slot:startingSchoolYearId name="form.starting_school_year_id"
                                    wire:model.live="form.starting_school_year_id"></x-slot:startingSchoolYearId>

                                @isset($model)
                                    @if (
                                        $form->course_id !== $model->student->course_id ||
                                            $form->starting_school_year_id !== $model->student->starting_school_year_id)
                                        <x-slot:realignSubjects name="form.realign_subjects"
                                            wire:model="form.realign_subjects"></x-slot:realignSubjects>
                                    @endif
                                    @if ($form->course_id !== $model->student->course_id)
                                        <x-slot:deleteSubjectsFromPreviousCourse name="form.delete_subjects_from_previous_course"
                                            wire:model="form.delete_subjects_from_previous_course"></x-slot:deleteSubjectsFromPreviousCourse>
                                    @endif
                                @endisset
                            @endif
                            @if ($form->include_password)
                                <x-slot:password name="form.password" wire:model="form.password"></x-slot:password>
                                <x-slot:passwordConfirmation name="form.password_confirmation"
                                    wire:model="form.password_confirmation">
                                </x-slot:passwordConfirmation>
                            @endif
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

        <div class="contents" x-show="showImportDialog">
            <x-modal-scrim />
            <x-dialog.container x-on:click.self="toggleImportDialog(); $dispatch('clear-inputs')">
                <x-dialog el="form" wire:submit.prevent="import" wire:key='student-form'>
                    <x-dialog.title>
                        Import Students
                    </x-dialog.title>
                    <x-dialog.content>
                        <x-form-control>
                            <x-file-drop
                                accept="*.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                :multiple="false">
                                <x-slot:input wire:model="import_file" x-ref="xInput">
                                </x-slot:input>
                            </x-file-drop>
                            <x-form-control.error-text key="import_file" />
                        </x-form-control>
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button type="button" x-on:click="toggleImportDialog(); $dispatch('clear-inputs')"
                            variant="text" color="neutral">Cancel</x-button>
                        <x-button type="submit">Upload</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        </div>
    </x-layouts.loader>
</div>
