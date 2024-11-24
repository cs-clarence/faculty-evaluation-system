<div class="contents">
    <div class="top flex justify-end mb-4">
        <button id="addCourseBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            wire:click='openForm'>
            Add Course
        </button>
    </div>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-200 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Course Code</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Course Name</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Department</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($courses as $course)
                        <tr class="cursor-pointer hover:bg-gray-100" data-course-id="{{ $course->id }}">
                            <td class="py-3 px-4 border-b">
                                {{ $course->code }}
                            </td>
                            <td class="py-3 px-4 border-b">{{ $course->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $course->department->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 border-b">
                                <button wire:click='edit({{ $course->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit
                                </button>
                                @if ($course->hasDependents())
                                    @isset($course->archived_at)
                                        <button wire:click='unarchive({{ $course->id }})'
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                            Unarchive
                                        </button>
                                    @else
                                        <button wire:click='archive({{ $course->id }})'
                                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
                                            title="This department has courses associated with it. You can only archive it until you delete those courses.">
                                            Archive
                                        </button>
                                    @endisset
                                @else
                                    <button wire:click='delete({{ $course->id }})'
                                        wire:confirm='Are you sure you want to delete this course?'
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 px-4 text-center text-gray-500">No courses found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <!-- Add Subject Modal -->
    @if ($this->isFormOpen)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($this->course)
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

                    <div class="flex justify-end">
                        <button type="button" id="cancelBtn" wire:click='closeForm'
                            class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @script
        <script>
            //Toggle modal for add subject
            document.getElementById('addCourseBtn').addEventListener('click', function() {
                document.getElementById('addCourseModal').classList.remove('hidden');
            });

            document.getElementById('cancelBtn').addEventListener('click', function() {
                document.getElementById('addCourseModal').classList.add('hidden');
            });

            //Course Subject
            document.addEventListener('DOMContentLoaded', function() {
                const dropdown = document.getElementById('subjectDropdown');
                const selectedSubjectsContainer = document.getElementById('selectedSubjectsContainer');
                const subjectsInput = document.getElementById('subjectsInput');
                let selectedSubjects = [];

                // Handle dropdown selection
                dropdown.addEventListener('change', function() {
                    const selectedValue = dropdown.value;
                    const selectedText = dropdown.options[dropdown.selectedIndex].text;

                    // Check if the subject is already selected
                    if (!selectedSubjects.includes(selectedValue)) {
                        // Add subject to selectedSubjects array
                        selectedSubjects.push(selectedValue);

                        // Update hidden input value
                        subjectsInput.value = selectedSubjects.join(',');

                        // Create and display the subject tag
                        const subjectTag = document.createElement('div');
                        subjectTag.className =
                            'inline-block bg-blue-200 text-blue-800 px-2 py-1 rounded-full m-1';
                        subjectTag.textContent = selectedText;

                        // Add a remove button to each tag
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'ml-2 text-red-600 hover:text-red-800';
                        removeBtn.textContent = 'x';
                        removeBtn.addEventListener('click', function() {
                            // Remove subject from selectedSubjects array
                            selectedSubjects = selectedSubjects.filter(id => id !== selectedValue);
                            subjectsInput.value = selectedSubjects.join(',');
                            // Remove the tag from the display
                            selectedSubjectsContainer.removeChild(subjectTag);
                        });

                        subjectTag.appendChild(removeBtn);
                        selectedSubjectsContainer.appendChild(subjectTag);
                    }

                    // Reset dropdown selection
                    dropdown.selectedIndex = 0;
                });
            });
        </script>
    @endscript
</div>
