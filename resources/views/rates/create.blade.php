<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Create New Rate</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/lucide@latest"></script>

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
            background-color: transparent;
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

        /* ========== Form Styles ========== */
        .form-group {
            margin-bottom: var(--space-lg);
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: var(--space-sm);
        }

        .form-select,
        .form-input {
            width: 100%;
            max-width: 300px;
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            color: var(--text);
            background-color: var(--white);
            transition: var(--transition);
        }

        .form-select:focus,
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-light);
        }

        .rate-fields {
            margin-top: var(--space-2xl);
            padding-top: var(--space-lg);
            border-top: 1px solid var(--border);
            display: none;
        }

        .rate-fields.show {
            display: block;
        }

        .rate-fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-xl);
            max-width: 600px;
        }

        .info-section {
            background-color: var(--primary-light);
            border: 1px solid #c7d2fe;
            border-radius: var(--radius-md);
            padding: var(--space-lg);
            margin: var(--space-xl) 0;
        }

        .info-header {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            margin-bottom: var(--space-sm);
        }

        .info-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0;
        }

        .info-content p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 0;
        }

        .form-actions {
            display: flex;
            gap: var(--space-md);
            margin-top: var(--space-2xl);
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
            padding: var(--space-sm) var(--space-lg);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: var(--border);
            color: var(--text);
            padding: var(--space-sm) var(--space-lg);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background-color: var(--border-dark);
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

            .rate-fields-grid {
                grid-template-columns: 1fr;
                gap: var(--space-lg);
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

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: var(--space-md) var(--space-lg);
            background-color: var(--success);
            color: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            z-index: 1000;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.error {
            background-color: var(--danger);
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
                <a href="{{ route('admin.showdashboard') }}" class="nav-item">
                    <i data-lucide="home" class="nav-icon"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="nav-item">
                    <i data-lucide="ticket" class="nav-icon"></i>
                    Coupons
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="nav-item">
                    <i data-lucide="calendar" class="nav-icon"></i>
                    Bookings
                </a>

                <!-- Manage Rates Dropdown -->
                <div class="nav-dropdown open" id="rates-dropdown">
                    <button class="nav-dropdown-toggle active" onclick="toggleDropdown('rates-dropdown')">
                        <div style="display: flex; align-items: center;">
                            <i data-lucide="dollar-sign" class="nav-icon"></i>
                            <span>Manage Rates</span>
                        </div>
                        <i data-lucide="chevron-down" class="dropdown-icon"></i>
                    </button>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('rates.create') }}" class="nav-dropdown-item active">
                            Create New Rate
                        </a>
                        <a href="{{ route('rates.edit') }}" class="nav-dropdown-item">
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
                <h2>Create New Rate</h2>
                <p>Add a new rate to your pricing system</p>
            </div>
            <div class="top-bar-actions">
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
            <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xl); color: var(--text);">Create
                New Rate</h1>

            <form id="rate-form" method="POST" action="{{ route('rates.store') }}">
                @csrf

                <div class="form-group">
                    <label for="rate-type" class="form-label">Select Category</label>
                    <select id="rate-type" name="category" class="form-select" required>
                        <option value="">Select Service</option>
                        <option value="frequencies">Service Frequency</option>
                        <option value="cleaning_types">Service Type</option>
                        <option value="square_footages">Home Size</option>
                        <option value="bedrooms">Bedrooms</option>
                        <option value="bathrooms">Bathrooms</option>
                        <option value="customOptions">Custom Options</option>
                    </select>
                </div>

                <!-- Rate Fields Section -->
                <div class="rate-fields" id="rate-fields">
                    <div class="rate-fields-grid">
                        <div class="form-group">
                            <label for="rate-name" class="form-label">Name</label>
                            <input type="text" id="rate-name" name="name" class="form-input"
                                placeholder="Enter rate name" required>
                        </div>

                        <div class="form-group">
                            <label for="rate-amount" class="form-label">Rate</label>
                            <input type="number" id="rate-amount" name="rate" class="form-input"
                                placeholder="Enter rate amount" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="form-group" id="image-upload-container"
                        style="display: none; margin-top: var(--space-lg);">
                        <label for="custom-image" class="form-label">Custom Image</label>
                        <input type="file" id="custom-image" name="custom_image" class="form-input"
                            accept="image/*">
                        <p style="font-size: 0.75rem; color: var(--text-light); margin-top: var(--space-xs);">
                            Upload an image for this custom option (optional)
                        </p>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-header">
                        <i data-lucide="info" style="width: 20px; height: 20px; color: var(--primary);"></i>
                        <h3>Rate Information</h3>
                    </div>
                    <div class="info-content">
                        <p>Select a rate type from the dropdown to display the input fields. Then, fill in the details
                            to create a new rate for your pricing system.</p>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" id="submit-btn" class="btn-primary">Create Rate</button>
                    <button type="button" class="btn-secondary" id="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        <span id="toast-message">Rate created successfully!</span>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Profile menu toggle
        const profileBtn = document.getElementById('profile-btn');
        const profileMenu = document.getElementById('profile-menu');

        if (profileBtn && profileMenu) {
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
        }

        // Dropdown toggle function
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.classList.toggle('open');
            }
        }

        // Form handling
        document.addEventListener('DOMContentLoaded', function() {
            const rateType = document.getElementById('rate-type');
            const rateFields = document.getElementById('rate-fields');
            const rateForm = document.getElementById('rate-form');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const cancelBtn = document.getElementById('cancel-btn');

            // Show/hide rate fields based on rate type selection
            if (rateType && rateFields) {
                rateType.addEventListener('change', function() {
                    rateFields.style.display = this.value ? 'block' : 'none';

                    const imageUploadContainer = document.getElementById('image-upload-container');
                    if (imageUploadContainer) {
                        imageUploadContainer.style.display = isCustomOption ? 'block' : 'none';
                    }
                });
            }

            // Show toast notification
            function showToast(message, isError = false) {
                if (!toast || !toastMessage) return;

                toastMessage.textContent = message;
                toast.className = 'toast'; // Reset classes

                if (isError) {
                    toast.classList.add('error');
                    const icon = toast.querySelector('i');
                    if (icon) {
                        icon.setAttribute('data-lucide', 'x-circle');
                        lucide.createIcons(); // Refresh icons
                    }
                }

                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }

            // Show/hide rate fields based on rate type selection
            if (rateType && rateFields) {
                rateType.addEventListener('change', function() {
                    const isCustomOption = this.value === 'customOptions';
                    rateFields.style.display = this.value ? 'block' : 'none';

                    // Show/hide image upload field based on selection
                    const imageUploadContainer = document.getElementById('image-upload-container');
                    if (imageUploadContainer) {
                        imageUploadContainer.style.display = isCustomOption ? 'block' : 'none';
                    }
                });
            }

            // Cancel button handler
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                        window.location.href = "{{ route('rates.index') }}";
                    }
                });
            }

            // Form submission handler - UPDATED FOR MULTIPLE TABLES
            // Form submission handler - UPDATED FOR MULTIPLE TABLES
            if (rateForm) {
                rateForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('submit-btn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = 'Creating...';
                    }

                    try {
                        const formData = new FormData(rateForm);
                        const category = formData.get('category');
                        const name = formData.get('name');
                        const rate = formData.get('rate');

                        // Validate required fields
                        if (!category || !name || !rate) {
                            throw new Error('Please fill all required fields');
                        }

                        const response = await fetch(rateForm.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: formData // Send as FormData instead of JSON
                        });

                        // First check if the response is JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            // If not JSON, get the text and see if it's an HTML error page
                            const text = await response.text();
                            throw new Error('Server returned an unexpected response');
                        }

                        // Now parse as JSON
                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to create rate');
                        }

                        showToast(data.message || 'Rate created successfully!');
                        rateForm.reset();
                        if (rateFields) rateFields.style.display = 'none';

                    } catch (error) {
                        console.error('Error:', error);
                        showToast(error.message || 'An error occurred', true);
                    } finally {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'Create Rate';
                        }
                    }
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nav-dropdown')) {
                    document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
                        dropdown.classList.remove('open');
                    });
                }
            });
        });
    </script>
</body>

</html>
