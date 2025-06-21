<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('styles')
    <style>
        /* ========== CSS Variables ========== */
        :root {
            /* Colors */
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --text: #111827;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --border: #e5e7eb;
            --border-dark: #d1d5db;
            --bg: #f9fafb;
            --white: #ffffff;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;

            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);

            /* Spacing */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;

            /* Radius */
            --radius-sm: 0.125rem;
            --radius: 0.25rem;
            --radius-md: 0.375rem;
            --radius-lg: 0.5rem;
            --radius-full: 9999px;

            /* Transition */
            --transition: all 0.2s ease;
        }

        /* ========== Base Styles ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            cursor: pointer;
            font-family: inherit;
            background: none;
            border: none;
        }

        /* ========== Layout Styles ========== */
        .flex-container {
            display: flex;
            min-height: 100vh;
        }

        /* ========== Sidebar Styles ========== */
        .sidebar {
            width: 240px;
            background-color: var(--white);
            border-right: 1px solid var(--border);
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: var(--space-lg) var(--space-xl);
            border-bottom: 1px solid var(--border);
        }

        .sidebar-header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }

        .sidebar nav {
            padding: var(--space-md) 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section {
            padding: 0 var(--space-xl) var(--space-sm);
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: var(--space-sm) var(--space-xl);
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-item:hover {
            background-color: var(--bg);
        }

        .nav-item.active {
            background-color: var(--primary-light);
            color: var(--primary);
            position: relative;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--primary);
            border-radius: 0 var(--radius) var(--radius) 0;
        }

        .nav-icon {
            margin-right: var(--space-md);
            width: 1rem;
            height: 1rem;
            stroke-width: 1.5;
        }

        /* ========== Dropdown Styles ========== */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--space-sm) var(--space-xl);
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            width: 100%;
            background: none;
            border: none;
        }

        .nav-dropdown-toggle:hover {
            background-color: var(--bg);
        }

        .nav-dropdown-toggle.active {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-icon {
            width: 1rem;
            height: 1rem;
            transition: transform 0.2s ease;
        }

        .nav-dropdown.open .dropdown-icon {
            transform: rotate(180deg);
        }

        .nav-dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: var(--bg);
        }

        .nav-dropdown.open .nav-dropdown-content {
            max-height: 200px;
        }

        .nav-dropdown-item {
            display: flex;
            align-items: center;
            padding: var(--space-sm) var(--space-xl);
            padding-left: 3.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 400;
            transition: var(--transition);
        }

        .nav-dropdown-item:hover {
            background-color: var(--white);
            color: var(--text);
        }

        .nav-dropdown-item.active {
            background-color:transparent;
            color: blue;
        }

        /* ========== Main Content Styles ========== */
        .main-content {
            flex: 1;
            margin-left: 240px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ========== Top Bar Styles ========== */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-md) var(--space-xl);
            background-color: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .page-title h2 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: var(--space-xs);
        }

        .page-title p {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .profile-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: var(--radius-full);
            background-color: var(--bg);
            transition: var(--transition);
        }

        .profile-btn:hover {
            background-color: var(--border);
        }

        /* ========== Profile Menu Styles ========== */
        .profile-menu {
            position: absolute;
            top: calc(100% + var(--space-sm));
            right: var(--space-xl);
            width: 240px;
            background-color: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            padding: var(--space-md);
            z-index: 50;
            display: none;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-header {
            padding-bottom: var(--space-sm);
            margin-bottom: var(--space-sm);
            border-bottom: 1px solid var(--border);
        }

        .profile-header p:first-child {
            font-weight: 600;
            margin-bottom: var(--space-xs);
        }

        .profile-header p:last-child {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            width: 100%;
            padding: var(--space-xs) 0;
            color: var(--text);
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .logout-btn:hover {
            color: var(--primary);
        }

        /* ========== Content Area Styles ========== */
        .content-area {
            flex: 1;
            padding: var(--space-xl);
            background-color: var(--white);
        }

        /* ========== Responsive Styles ========== */
        @media (max-width: 1024px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: var(--space-lg);
            }
        }

        @media (max-width: 640px) {
            .top-bar {
                padding: var(--space-sm) var(--space-lg);
            }

            .content-area {
                padding: var(--space-md);
            }
        }

        /* ========== Utility Classes ========== */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
    </style>
</head>

<body class="flex-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Admin Panel</h1>
        </div>
        <nav>
            <div class="nav-section">
                <p class="nav-section-title">Menu</p>
            </div>
            <div>
                <a href="{{ route('admin.showdashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.showdashboard') ? 'active' : '' }}">
                    <i data-lucide="home" class="nav-icon"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.coupons.index') }}"
                    class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
                    onclick="event.preventDefault(); window.location.href=this.href;">
                    <i data-lucide="ticket" class="nav-icon"></i>
                    Coupons
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                    class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i data-lucide="calendar" class="nav-icon"></i>
                    Bookings
                </a>

                <!-- Manage Rates Dropdown -->
                <div class="nav-dropdown {{ request()->routeIs('rates.*') ? 'open' : '' }}" id="rates-dropdown">
                    <button class="nav-dropdown-toggle {{ request()->routeIs('rates.*') ? 'active' : '' }}" onclick="toggleDropdown('rates-dropdown')">
                        <div style="display: flex; align-items: center;">
                            <i data-lucide="dollar-sign" class="nav-icon"></i>
                            <span>Manage Rates</span>
                        </div>
                        <i data-lucide="chevron-down" class="dropdown-icon"></i>
                    </button>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('rates.create') }}"
                           class="nav-dropdown-item {{ request()->routeIs('rates.create') ? 'active' : '' }}">
                            Create New Rate
                        </a>
                        <a href="{{ route('rates.edit') }}"
                           class="nav-dropdown-item {{ request()->routeIs('rates.update')  ? 'active' : '' }}">
                            Update Rate
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="page-title">
                <h2>@yield('page-title')</h2>
                <p>@yield('page-description')</p>
            </div>
            <div class="top-bar-actions">
                @yield('top-bar-actions')
                <button class="profile-btn" id="profile-btn">
                    <i data-lucide="user" style="width: 20px; height: 20px;"></i>
                    <span class="sr-only">User Profile</span>
                </button>
            </div>

            <!-- Profile Menu -->
            <div class="profile-menu" id="profile-menu">
                <div class="profile-header">
                    @auth
                        <p>{{ Auth::user()->name }}</p>
                        <p>{{ Auth::user()->email }}</p>
                    @else
                        <p>Guest User</p>
                        <a href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Body Content -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    @yield('modals')

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Profile menu toggle
        const profileBtn = document.getElementById('profile-btn');
        const profileMenu = document.getElementById('profile-menu');

        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', function() {
            if (profileMenu.classList.contains('show')) {
                profileMenu.classList.remove('show');
            }
        });

        // Dropdown toggle function
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('open');
        }

        // Mobile sidebar toggle (you'll need to add a button for this)
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('open');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-dropdown')) {
                document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
            }
        });
    </script>
</body>

</html>
