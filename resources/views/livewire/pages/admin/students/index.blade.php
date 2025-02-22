@php
    use App\Models\RoleCode;
@endphp
<div class="contents">
    <x-sections.header title="Students">
        <x-button id="addSubjectBtn" wire:click='openForm'>
            Add Student
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
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
                                'edit_password' => [
                                    'order' => 1.1,
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
</div>
