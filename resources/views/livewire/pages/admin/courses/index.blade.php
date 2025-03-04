<div class="contents">
    <x-sections.header title="Courses">
        <x-button id="addCourseBtn" wire:click='openForm'>
            Add Course
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->
        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Course Code', 'render' => 'code'],
                    ['label' => 'Course Name', 'render' => 'name'],
                    ['label' => 'Semesters', 'render' => 'course_semesters_count'],
                    ['label' => 'Subjects', 'render' => 'course_subjects_count'],
                    ['label' => 'Department', 'render' => 'department.name'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [
                                'view' => [
                                    'order' => 0,
                                    'type' => 'link',
                                    'label' => 'View',
                                    'color' => 'primary',
                                    'href' => fn($data) => route('admin.courses.course', ['course' => $data->id]),
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp
            <x-table :data="$courses" :columns="$columns">
                <x-slot:actions>
                    <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                </x-slot:actions>
            </x-table>
        </div>

    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <x-modal-scrim />
        <x-dialog.container wire:click.self="closeForm">
            <x-dialog el="form" wire:submit.prevent="save" wire:key="course-form">
                <x-dialog.title>
                    @isset($course)
                        Edit Course
                    @else
                        Add New Course
                    @endisset
                </x-dialog.title>
                <x-dialog.content>
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <x-form-control>
                        <x-form-control.label key="form.name">Course Name</x-form-control.label>
                        <x-input type="text" key="form.name" required wire:model="form.name" />
                        <x-form-control.error-text key="form.name" />
                    </x-form-control>

                    <x-form-control>
                        <x-form-control.label key="form.code">Course Code</x-form-control.label>
                        <x-input type="text" key="form.code" required wire:model="form.code" />
                        <x-form-control.error-text key="form.code" />
                    </x-form-control>

                    <x-form-control>
                        <x-form-control.label key="form.department_id">Department</x-form-control.label>
                        <x-combobox key="form.department_id" required :options="$departments" :label="fn($i) => $i->name"
                            wire:model="form.department_id" :value="fn($i) => $i->id" placeholder="Select department"
                            empty="No Departments Available" />
                        <x-form-control.error-text key="form.department_id" />
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
