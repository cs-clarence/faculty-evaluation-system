<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>@yield('title', 'Teacher\'s Evaluation System')</title>

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
    @livewireStyles
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
                <li><a href="{{ route('home') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Home</span>
                    </a></li>

                <li><a href="" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Profile</span>
                    </a></li>

                <li><a href="{{ route('school-years.index') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>School Years</span>
                    </a></li>

                <li><a href="{{ route('departments.index') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Department</span>
                    </a></li>

                <li><a href="{{ route('courses.index') }}" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class='bx bx-user'></i>
                        <span>Course</span>
                    </a></li>

                <li><a href="{{ route('subjects.index') }}" class="flex items-center space-x-2 hover:text-gray-300">
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

    <div class="flex">
        <section id="mainContent" class="main-content flex-1 p-6 transition-all duration-300 ml-0">
            @yield('content')
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
    @livewireScripts
</body>

</html>
