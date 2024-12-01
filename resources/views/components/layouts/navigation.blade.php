@props(['title' => 'SPCF-TES Admin', 'links' => []])
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
                transition: transform 0.3s ease;
            }

            .nav-link-open {
                margin-left: 256px;
                /* Same width as the sidebar */
            }

            .hide-overflow {
                overflow: hidden;
            }
        </style>
        @isset($head)
            {{ $head }}
        @endisset
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
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['href'] }}" @if ($link['use_wire_navigate'] ?? true) wire:navigate @endif
                            class="flex items-center space-x-2 hover:text-gray-300">
                            <i class='bx bx-user'></i>
                            <span>{{ $link['title'] }}</span>
                        </a>
                    </li>
                @endforeach
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
        class="fixed top-4 left-4 text-2xl text-gray-600 z-50 focus:outline-none hover:text-gray-400">
        <i class="uil uil-bars"></i>
    </button>


    <div class="flex flex-col min-h-dvh">
        <!-- nav link -->
        <div id="navLink"
            class="flex bg-indigo-950 h-16 text-white font-serif p-4 transition-all duration-300 sticky top-0 left-0 z-40">
            <div class="flex ml-8 items-center justify-center text-xl">
                <span>SPCF - TEACHERS EVALUATION SYSTEM</span>
            </div>
        </div>
        <section id="mainContent" class="main-content flex flex-col flex-1 p-6 transition-all duration-300 ml-0">
            @isset($slot)
                {{ $slot }}
            @else
                Blank Page
            @endisset
        </section>
    </div>
    <!-- JavaScript for Sidebar Toggle -->
    @push('scripts')
        <script>
            // Sidebar toggle functionality
            function toggleSidebar(forceClose = false) {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                const navLink = document.getElementById('navLink');
                const body = document.querySelector('body');

                const open = () => {
                    sidebar.classList.remove('sidebar-closed');
                    sidebar.classList.add('sidebar-open');
                    body.classList.add('hide-overflow'); // Prevent body scroll
                    mainContent.style.transform = 'translateX(256px)'; // Adjust margin to prevent content overlap
                    sidebarToggle.style.transform = 'translateX(256px)'; // Move the button along with sidebar
                    navLink.classList.add('nav-link-open'); // Add the margin class to nav link
                }

                const close = () => {
                    sidebar.classList.remove('sidebar-open');
                    sidebar.classList.add('sidebar-closed');
                    body.classList.remove('hide-overflow'); // Enable body scroll
                    mainContent.style.transform = ''; // Reset margin
                    sidebarToggle.style.transform = 'translateX(0)'; // Reset button position
                    navLink.classList.remove('nav-link-open'); // Remove the margin class from nav link
                }

                if (forceClose) {
                    if (!sidebar.classList.contains('sidebar-closed')) {
                        close();
                    }
                } else {
                    // Toggle classes to open/close sidebar
                    if (sidebar.classList.contains('sidebar-closed')) {
                        open();
                    } else {
                        close();
                    }
                }

            }

            function init() {
                const sidebarToggle = document.getElementById('sidebarToggle');
                sidebarToggle.addEventListener('click', () => toggleSidebar());
                const mainContent = document.getElementById('mainContent');
                mainContent.addEventListener('click', () => toggleSidebar(true));
                const links = document.querySelectorAll('#sidebar a');

                for (const link of links) {
                    link.addEventListener('click', () => toggleSidebar());
                }
            }
            init();
        </script>
    @endpush
</x-layouts.app>
