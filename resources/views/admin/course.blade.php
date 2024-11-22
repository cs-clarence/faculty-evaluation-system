<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Teacher's Evaluation System</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    <style>
        /* Additional CSS for sidebar positioning and transitions */
        #sidebar {
            transition: transform 0.3s ease;
        }

        .sidebar-open {
            transform: translateX(0);
        }

        .sidebar-closed {
            transform: translateX(-100%);
        }

        .main-content {
            transition: margin-left 0.1s ease, width 0.3s ease;
        }

        .nav-link-open {
            margin-left: 256px;
            /* Same width as the sidebar */
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <nav id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-blue-900 text-white p-4 sidebar-closed">
        <div class="flex flex-col h-full">
            <!-- Logo and Name -->
            <div class="flex items-center space-x-4 mb-8">
                <div class="logo-image">
                    <img src="images/logo.png" alt="Logo" class="w-12 h-12">
                </div>
                <span class="font-semibold text-xl">Evaluation System</span>
            </div>

            <!-- Navigation Links -->
            <ul class="flex-1 space-y-4">
                <li><a href="{{ route('home') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Home</span>
                    </a></li>

                <li><a href="" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Profile</span>
                    </a></li>

                <li><a href="{{ route('department') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Department</span>
                    </a></li>

                <li><a href="{{ route('course') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Course</span>
                    </a></li>

                <li><a href="{{ route('subject') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Subject</span>
                    </a></li>

                <li><a href="" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Accounts</span>
                    </a></li>
            </ul>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="flex items-center space-x-2 hover:text-gray-300">
                    <i class="uil uil-signout"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Sidebar Toggle Button -->
    <button id="sidebarToggle"
        class="fixed top-4 left-4 text-2xl text-gray-600 z-20 focus:outline-none hover:text-gray-400">
        <i class="uil uil-bars"></i>
    </button>

    <!-- nav link -->
    <div id="navLink" class="flex bg-indigo-950 h-16 text-white font-serif p-4 transition-all duration-300">
        <div class="flex ml-8 items-center justify-center text-xl">
            <span>TEACHERS EVALUATION SYSTEM</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex">
        <!-- Dashboard Section -->
        <section id="mainContent" class="main-content flex-1 p-6 transition-all duration-300 ml-0">
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
                                            {{ $course->course_name }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 border-b">{{ $course->course_name }}</td>
                                    <td class="py-3 px-4 border-b">{{ $course->department->department_name ?? 'N/A' }}
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
            <div id="addCourseModal"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
                <div class="bg-white p-6 rounded-lg w-96">
                    <h3 class="text-lg font-semibold mb-4">Add New Course</h3>

                    <!-- Add Subject Form -->
                    <form id="addSubjectForm" method="POST" action="{{ route('courses.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="subject_code" class="block text-gray-700">Course Code</label>
                            <input type="text" name="course_code" id="course_code" required
                                class="w-full px-3 py-2 border rounded-lg">
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
                            <label for="subject_name" class="block text-gray-700">Course Name</label>
                            <input type="text" name="course_name" id="course_name" required
                                class="w-full px-3 py-2 border rounded-lg">
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="block text-gray-700">Course Department</label>
                            <select id="subjectDropdown" name="department_id"
                                class="w-full px-3 py-2 border rounded-lg">
                                <option value="" disabled selected>Select a department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_code }}</option>
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
        </section>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.getElementById('mainContent');
        const navLink = document.getElementById('navLink');

        sidebarToggle.addEventListener('click', () => {
            // Toggle classes to open/close sidebar
            if (sidebar.classList.contains('sidebar-closed')) {
                sidebar.classList.remove('sidebar-closed');
                sidebar.classList.add('sidebar-open');
                mainContent.style.marginLeft = '256px'; // Adjust margin to prevent content overlap
                sidebarToggle.style.transform = 'translateX(256px)'; // Move the button along with sidebar
                navLink.classList.add('nav-link-open'); // Add the margin class to nav link
            } else {
                sidebar.classList.remove('sidebar-open');
                sidebar.classList.add('sidebar-closed');
                mainContent.style.marginLeft = '0'; // Reset margin
                sidebarToggle.style.transform = 'translateX(0)'; // Reset button position
                navLink.classList.remove('nav-link-open'); // Remove the margin class from nav link
            }
        });

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

</body>

</html>
