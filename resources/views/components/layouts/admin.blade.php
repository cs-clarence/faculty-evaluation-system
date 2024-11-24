@props(['title' => 'SPCF-TES Admin'])

<x-layouts.app :title="$title">
    <x-slot:head>
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
    </x-slot:head>


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
                <li><a href="{{ route('admin.dashboard.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Dashboard</span>
                    </a></li>

                <li><a href="{{ route('admin.school-years.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>School Years</span>
                    </a></li>

                <li><a href="{{ route('admin.departments.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Departments</span>
                    </a></li>

                <li><a href="{{ route('admin.courses.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Courses</span>
                    </a></li>

                <li><a href="{{ route('admin.subjects.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Subjects</span>
                    </a></li>

                <li><a href="{{ route('admin.sections.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Sections</span>
                    </a></li>

                <li><a href="{{ route('admin.teachers.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Teachers</span>
                    </a></li>

                <li><a href="{{ route('admin.students.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Students</span>
                    </a></li>

                <li><a href="{{ route('admin.accounts.index') }}"
                        class="flex items-center space-x-2 hover:text-gray-300">
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

    <div class="flex">
        <section id="mainContent" class="main-content flex-1 p-6 transition-all duration-300 ml-0">
            @isset($slot)
                {{ $slot }}
            @else
                Blank Page
            @endisset
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
    </script>
</x-layouts.app>
