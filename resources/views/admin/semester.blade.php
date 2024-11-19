<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Teacher's Evaluation System</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            margin-left: 256px; /* Same width as the sidebar */
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
                <img src="/images/logo.png" alt="Logo" class="w-12 h-12">
            </div>
            <span class="font-semibold text-xl">Evaluation System</span>
        </div>
        
        <!-- Navigation Links -->
        <ul class="flex-1 space-y-4">
            <li><a href="{{route('home')}}" class="flex items-center space-x-2 hover:text-gray-300">
                <i class='bx bx-user'></i>
                <span>Home</span>
            </a></li>

            <li><a href="" class="flex items-center space-x-2 hover:text-gray-300">
                <i class='bx bx-user'></i>
                <span>Profile</span>
            </a></li>

            <li><a href="{{route('department')}}" class="flex items-center space-x-2 hover:text-gray-300">
                <i class='bx bx-user'></i>
                <span>Department</span>
            </a></li>

            <li><a href="{{route('course')}}" class="flex items-center space-x-2 hover:text-gray-300">
                <i class='bx bx-user'></i>
                <span>Course</span>
            </a></li>

            <li><a href="{{route('subject')}}" class="flex items-center space-x-2 hover:text-gray-300">
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
            <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center space-x-2 hover:text-gray-300">
                <i class="uil uil-signout"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>

<!-- Sidebar Toggle Button -->
<button id="sidebarToggle" class="fixed top-4 left-4 text-2xl text-gray-600 z-20 focus:outline-none hover:text-gray-400">
    <i class="uil uil-bars"></i>
</button>

<!-- nav link -->
<div id="navLink" class="flex bg-indigo-950 h-16 text-white font-serif p-4 transition-all duration-300">
    <div class="flex ml-8 items-center justify-center text-xl">
        <span>TEACHERS EVALUATION SYSTEM</span>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="p-6">
    <!-- Course Semesters Page -->
    <div class="container mx-auto p-4">

        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold">{{ $course->course_name }} - Course Semesters</h1>
            <button id="addSemesterBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Add Semester
            </button>
        </div>

        <!-- Course Semesters Table -->
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-4 text-left">Year Level</th>
                    <th class="py-3 px-4 text-left">Semester</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courseSemesters as $semester)
                    <tr class="hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $semester->year_level }}</td>
                        <td class="py-3 px-4">{{ $semester->semester }}</td>
                        <td class="py-3 px-4">
                            <!-- You can add delete/edit actions here -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- Add Semester Modal -->
    <div id="addSemesterModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Add New Semester</h3>

            <form id="addSemesterForm" method="POST" action="{{ route('courses.storeSemester', $course->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="year_level" class="block text-gray-700">Year Level</label>
                    <input type="text" name="year_level" id="year_level" required class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="semester" class="block text-gray-700">Semester</label>
                    <input type="text" name="semester" id="semester" required class="w-full px-3 py-2 border rounded-lg">
                </div>

                <!-- Multi-Select Dropdown for Subjects -->
<div class="mb-4">
    <label for="subject" class="block text-gray-700">Select Subjects</label>
    <div id="subjectDropdownContainer" class="relative">
        <!-- Dropdown button -->
        <div id="subjectDropdownButton" class="w-full px-3 py-2 border rounded-lg bg-white cursor-pointer">
            <span id="subjectDropdownPlaceholder" class="text-gray-500">Select subjects</span>
        </div>

        <!-- Dropdown items (hidden by default) -->
        <div id="subjectDropdownMenu" class="absolute left-0 right-0 z-10 mt-2 bg-white border rounded-lg shadow-lg hidden max-h-48 overflow-y-auto">
            @foreach ($subjects as $subject)
            <div 
                class="px-4 py-2 hover:bg-gray-200 cursor-pointer subject-item" 
                data-subject-id="{{ $subject->id }}" 
                data-subject-name="{{ $subject->subject_name }}"
            >
                {{ $subject->subject_name }}
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Display Selected Subjects -->
<div class="mb-4">
    <label for="selectedSubjects" class="block text-gray-700">Selected Subjects</label>
    <div id="selectedSubjectsContainer" class="w-full px-3 py-2 border rounded-lg bg-gray-100 min-h-[50px] flex flex-wrap gap-2">
        <!-- Selected subjects will be displayed as tags -->
    </div>
</div>

<!-- Hidden input field to store selected subject IDs -->
<input type="hidden" name="subjects[]" id="subjectsInput">



                <div class="flex justify-end">
                    <button type="button" id="cancelBtn" class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>

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
                selectedSubjects.push({ id: subjectId, name: subjectName });

                // Create a tag to display the selected subject
                const tag = document.createElement('div');
                tag.className = 'px-3 py-1 bg-blue-200 text-gray-700 rounded-lg flex items-center gap-2';
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

</body>
</html>
