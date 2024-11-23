@extends('layouts.admin')
@section('content')
    <div class="top flex justify-end mb-4">
        <button id="addCourseBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
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
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($courses as $course)
                        <tr class="cursor-pointer hover:bg-gray-100" data-course-id="{{ $course->id }}"
                            onclick="showModal(this)">
                            <td class="py-3 px-4 border-b">
                                <a href="{{ route('courses.showSemesters', $course->id) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $course->name }}
                                </a>
                            </td>
                            <td class="py-3 px-4 border-b">{{ $course->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $course->department->name ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-center text-gray-500">No subjects found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <!-- Add Subject Modal -->
    <div id="addCourseModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Add New Course</h3>

            <!-- Add Subject Form -->
            <form id="addSubjectForm" method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block text-gray-700">Course Code</label>
                    <input type="text" name="code" id="code" required class="w-full px-3 py-2 border rounded-lg">
                </div>

                <!-- INPUT MULTIPLE SUBJECTS
            <div class="mb-4">
            <label for="subject" class="block text-gray-700">Course Subject</label>
                <select id="subjectDropdown" class="w-full px-3 py-2 border rounded-lg">
                    <option value="" disabled selected>Select a subject</option>
                    @foreach ($subjects as $subject)
    <option value="{{ $subject->id }}">{{ $subject->subjectID }}</option>
    @endforeach
                </select>
            </div> -->

                <!-- Display Selected Subjects
            <div class="mb-4">
                <label for="selectedSubjects" class="block text-gray-700">Selected Subjects</label>
                <div id="selectedSubjectsContainer" class="w-full px-3 py-2 border rounded-lg bg-gray-100 min-h-[50px]">

                </div>
            </div> -->

                <!-- <input type="hidden" name="subjects[]" id="subjectsInput">   -->


                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Course Name</label>
                    <input type="text" name="name" id="name" required class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="subject" class="block text-gray-700">Course Department</label>
                    <select id="subjectDropdown" name="department_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="" disabled selected>Select a department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->name }}
                                ({{ $department->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

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
@endsection
