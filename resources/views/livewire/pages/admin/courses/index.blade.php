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
        <div class="fixed inset-0 bg-gray-900/50 flex justify-center items-center" wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($course)
                    <h3 class="text-lg font-semibold mb-4">Edit Course</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Course</h3>
                @endisset

                <!-- Add Subject Form -->
                <form id="addSubjectForm" wire:submit="save" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <div class="mb-4">
                        <label for="code" class="block text-gray-700">Course Code</label>
                        <input type="text" name="code" id="code" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.code">
                        @error('form.code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Course Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.name">
                        @error('form.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="subject" class="block text-gray-700">Department</label>
                        <select id="subjectDropdown" name="department_id" class="w-full px-3 py-2 border rounded-lg"
                            wire:model.defer="form.department_id">
                            <option value="" selected>Select a department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">
                                    {{ $department->name }}
                                    ({{ $department->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('form.department_id')
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
