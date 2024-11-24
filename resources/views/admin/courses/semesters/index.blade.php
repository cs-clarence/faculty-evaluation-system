<x-layouts.admin>
    <div class="container mx-auto p-4">

        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold">{{ $course->name }} - Course Semesters</h1>
            <button id="addSemesterBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Add Semester
            </button>
        </div>

        <!-- Course Semesters Table -->
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-4 text-left">Year</th>
                    <th class="py-3 px-4 text-left">Semester</th>
                    <th class="py-3 px-4 text-left">Subjects</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courseSemesters as $semester)
                    @php
                        $subjects = $semester->subjects;
                        $subjectsText = implode(', ', $subjects->pluck('name')->toArray());
                    @endphp
                    <tr class="">
                        <td class="py-3 px-4">{{ $semester->year_level }}</td>
                        <td class="py-3 px-4">{{ $semester->semester }}</td>
                        <td class="py-3 px-4">{{ $subjectsText }}</td>
                        <td class="py-3 px-4">
                            <!-- You can add delete/edit actions here -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- Add Semester Modal -->
    <div id="addSemesterModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Add New Semester</h3>

            <form id="addSemesterForm" method="POST" action="{{ route('courses.storeSemester', $course->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="year_level" class="block text-gray-700">Year Level</label>
                    <input type="text" name="year_level" id="year_level" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="semester" class="block text-gray-700">Semester</label>
                    <input type="text" name="semester" id="semester" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                <!-- Multi-Select Dropdown for Subjects -->
                <div class="mb-4">
                    <label for="subject" class="block text-gray-700">Select Subjects</label>
                    <div id="subjectDropdownContainer" class="relative">
                        <!-- Dropdown button -->
                        <div id="subjectDropdownButton"
                            class="w-full px-3 py-2 border rounded-lg bg-white cursor-pointer">
                            <span id="subjectDropdownPlaceholder" class="text-gray-500">Select subjects</span>
                        </div>

                        <!-- Dropdown items (hidden by default) -->
                        <div id="subjectDropdownMenu"
                            class="absolute left-0 right-0 z-10 mt-2 bg-white border rounded-lg shadow-lg hidden max-h-48 overflow-y-auto">
                            @foreach ($subjects as $subject)
                                <div class="px-4 py-2 hover:bg-gray-200 cursor-pointer subject-item"
                                    data-subject-id="{{ $subject->id }}" data-subject-name="{{ $subject->name }}">
                                    {{ $subject->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Display Selected Subjects -->
                <div class="mb-4">
                    <label for="selectedSubjects" class="block text-gray-700">Selected Subjects</label>
                    <div id="selectedSubjectsContainer"
                        class="w-full px-3 py-2 border rounded-lg bg-gray-100 min-h-[50px] flex flex-wrap gap-2">
                        <!-- Selected subjects will be displayed as tags -->
                    </div>
                </div>

                <!-- Hidden input field to store selected subject IDs -->
                <input type="hidden" name="subjects[]" id="subjectsInput">



                <div class="flex justify-end">
                    <button type="button" id="cancelBtn"
                        class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Toggle modal for adding semester
        document.getElementById('addSemesterBtn').addEventListener('click', function() {
            document.getElementById('addSemesterModal').classList.remove('hidden');
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('addSemesterModal').classList.add('hidden');
        });
        //Course Subject
        document.addEventListener('DOMContentLoaded', () => {
            const subjectDropdownButton = document.getElementById('subjectDropdownButton');
            const subjectDropdownMenu = document.getElementById('subjectDropdownMenu');
            const subjectDropdownPlaceholder = document.getElementById('subjectDropdownPlaceholder');
            const selectedSubjectsContainer = document.getElementById('selectedSubjectsContainer');
            const subjectsInput = document.getElementById('subjectsInput');

            const selectedSubjects = []; // Array to hold selected subjects

            // Toggle dropdown menu visibility
            subjectDropdownButton.addEventListener('click', () => {
                subjectDropdownMenu.classList.toggle('hidden');
            });

            // Handle subject selection
            subjectDropdownMenu.addEventListener('click', (event) => {
                const subjectItem = event.target.closest('.subject-item');
                if (subjectItem) {
                    const subjectId = subjectItem.dataset.subjectId;
                    const subjectName = subjectItem.dataset.subjectName;

                    // Check if subject is already selected
                    const alreadySelected = selectedSubjects.some((sub) => sub.id === subjectId);

                    if (!alreadySelected) {
                        // Add subject to selected list
                        selectedSubjects.push({
                            id: subjectId,
                            name: subjectName
                        });

                        // Create a tag to display the selected subject
                        const tag = document.createElement('div');
                        tag.className =
                            'px-3 py-1 bg-blue-200 text-gray-700 rounded-lg flex items-center gap-2';
                        tag.dataset.subjectId = subjectId;
                        tag.innerHTML = `
                    <span>${subjectName}</span>
                    <button type="button" class="remove-tag text-gray-500 hover:text-red-500">
                        &times;
                    </button>
                `;

                        selectedSubjectsContainer.appendChild(tag);

                        // Add event listener for removing the tag
                        tag.querySelector('.remove-tag').addEventListener('click', () => {
                            // Remove from selectedSubjects array
                            const index = selectedSubjects.findIndex((sub) => sub.id === subjectId);
                            if (index !== -1) selectedSubjects.splice(index, 1);

                            // Remove the tag from the UI
                            tag.remove();

                            // Update hidden input
                            updateSubjectsInput();
                        });

                        // Update the dropdown placeholder
                        updateSubjectsInput();
                    }
                }
            });

            // Update the hidden input field with selected subject IDs
            function updateSubjectsInput() {
                const selectedSubjectIds = selectedSubjects.map((sub) => sub.id);
                subjectsInput.value = selectedSubjectIds.join(',');

                // Update placeholder if no subjects are selected
                if (selectedSubjectIds.length === 0) {
                    subjectDropdownPlaceholder.textContent = 'Select subjects';
                } else {
                    subjectDropdownPlaceholder.textContent = `${selectedSubjectIds.length} subject(s) selected`;
                }
            }
        });
    </script>
</x-layouts.admin>
