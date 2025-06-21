<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Create New Rate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
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

        /* Base Delete Button Style */
        .btn-danger.btn-sm.delete-frequency {
            position: relative;
            padding: 0.35rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #ef4444;
            /* Red-500 */
            color: white;
            border: 1px solid #dc2626;
            /* Red-600 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Hover State */
        .btn-danger.btn-sm.delete-frequency:hover {
            background-color: #dc2626;
            /* Red-600 */
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Active/Pressed State */
        .btn-danger.btn-sm.delete-frequency:active {
            background-color: #b91c1c;
            /* Red-700 */
            transform: translateY(0);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Focus State */
        .btn-danger.btn-sm.delete-frequency:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3);
            /* Red-500 with opacity */
        }

        /* Disabled State */
        .btn-danger.btn-sm.delete-frequency:disabled {
            background-color: #fca5a5;
            /* Red-300 */
            border-color: #fca5a5;
            /* Red-300 */
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Loading State (when deleting) */
        .btn-danger.btn-sm.delete-frequency.deleting {
            color: transparent;
            pointer-events: none;
        }

        .btn-danger.btn-sm.delete-frequency.deleting::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid rgba(187, 18, 18, 0.8);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* Animation for loading spinner */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* For dark mode compatibility (optional) */
        @media (prefers-color-scheme: dark) {
            .btn-danger.btn-sm.delete-frequency {
                background-color: #b91c1c;
                /* Red-700 */
                border-color: #991b1b;
                /* Red-800 */
            }

            .btn-danger.btn-sm.delete-frequency:hover {
                background-color: #991b1b;
                /* Red-800 */
            }
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

        /* Root Variables */
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --text: #1f2937;
            --text-secondary: #6b7280;
            --bg: #f9fafb;
            --white: #ffffff;
            --border: #e5e7eb;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --success: #10b981;
            --error: #ef4444;
            --radius: 0.375rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
        }

        /* General Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--space-lg);
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: var(--space-lg);
            text-align: center;
        }

        .admin-pricing-container {
            display: grid;
            gap: var(--space-lg);
        }

        /* Card Styling */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            padding: var(--space-lg);
            border: 1px solid var(--border);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: var(--space-md);
        }

        .sub-section-title {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text);
            margin: var(--space-md) 0 var(--space-sm);
        }

        /* Table Styling */
        .pricing-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .pricing-table th,
        .pricing-table td {
            padding: var(--space-sm) var(--space-md);
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .pricing-table th {
            background: var(--bg);
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .pricing-table tr:last-child td {
            border-bottom: none;
        }

        .pricing-table tr:hover {
            background: var(--bg);
        }

        /* Input Styling */
        .form-control {
            width: 100%;
            max-width: 150px;
            padding: var(--space-sm);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: var(--text);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        /* Checkbox Styling */
        .checkbox-container {
            display: inline-block;
            position: relative;
            padding-left: 1.75rem;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 1rem;
            width: 1rem;
            background-color: var(--white);
            border: 1px solid var(--border);
            border-radius: 0.25rem;
        }

        .checkbox-container input:checked~.checkmark {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked~.checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 0.35rem;
            top: 0.15rem;
            width: 0.25rem;
            height: 0.5rem;
            border: solid var(--white);
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Button Styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s, transform 0.2s;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-saving::after {
            content: "";
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-left: 8px;
            border: 2px solid transparent;
            border-top-color: var(--white);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Image Preview */
        .image-preview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--space-sm);
        }

        .addon-image-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .addon-image {
            max-width: 150px;
            font-size: 0.75rem;
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 24px;
            border-radius: var(--radius);
            color: var(--white);
            z-index: 1000;
            display: none;
            box-shadow: var(--shadow-md);
            animation: slideIn 0.3s ease forwards, slideOut 0.3s ease 2.7s forwards;
        }

        .toast.success {
            background: var(--success);
        }

        .toast.error {
            background: var(--error);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: var(--space-md);
            }

            .pricing-table th,
            .pricing-table td {
                padding: var(--space-sm);
            }

            .form-control {
                max-width: 120px;
            }

            .btn {
                padding: var(--space-sm);
            }

            .addon-image {
                max-width: 100px;
            }
        }

        @media (max-width: 576px) {
            .pricing-table {
                font-size: 0.75rem;
            }

            .form-control {
                max-width: 100px;
            }

            .btn {
                font-size: 0.75rem;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .sub-section-title {
                font-size: 1rem;
            }
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
                    <button class="nav-dropdown-toggle active " onclick="toggleDropdown('rates-dropdown')">
                        <div style="display: flex; align-items: center;">
                            <i data-lucide="dollar-sign" class="nav-icon"></i>
                            <span>Manage Rates</span>
                        </div>
                        <i data-lucide="chevron-down" class="dropdown-icon"></i>
                    </button>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('rates.create') }}" class="nav-dropdown-item ">
                            Create New Rate
                        </a>
                        <a href="{{ route('rates.edit') }}" class="nav-dropdown-item active ">
                            Update Rate
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div class="container">
        <h1 class="page-title">Service Pricing Management</h1>

        <div class="admin-pricing-container">
            <!-- Service Frequencies Section -->
            <div class="pricing-section card">
                <h2 class="section-title">Service Frequencies</h2>
                <div class="table-responsive">
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Discount Rate (%)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="frequencies-tbody">
                            @foreach ($frequencies as $frequency)
                                <tr id="row-{{ $frequency->id }}">
                                    <td>{{ $frequency->name }}</td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control discount-rate"
                                            value="{{ $frequency->discount_rate }}" data-id="{{ $frequency->id }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm save-frequency"
                                            data-id="{{ $frequency->id }}">Save</button>
                                        <button class="btn btn-danger btn-sm delete-frequency"
                                            data-id="{{ $frequency->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Service Types Section -->
            <div class="pricing-section card">
                <h2 class="section-title">Service Types</h2>
                <div class="table-responsive">
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Base Rate ($)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cleaningTypes as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control service-rate"
                                            value="{{ $type->rate }}" data-id="{{ $type->id }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm save-service-type"
                                            data-id="{{ $type->id }}">Save</button>
                                        <button class="btn btn-danger btn-sm delete-service-type"
                                            data-id="{{ $type->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Property Details Section -->
            <div class="pricing-section card">
                <h2 class="section-title">Property Details Pricing</h2>

                <!-- Square Footage -->
                <h3 class="sub-section-title">Home Size</h3>
                <div class="table-responsive">
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Additional Rate ($)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($squareFootages as $size)
                                <tr>
                                    <td>{{ $size->name }}</td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control size-rate"
                                            value="{{ $size->rate }}" data-id="{{ $size->id }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm save-size"
                                            data-id="{{ $size->id }}">Save</button>
                                        <button class="btn btn-danger btn-sm delete-size"
                                            data-id="{{ $size->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bedrooms -->
                <h3 class="sub-section-title">Bedrooms</h3>
                <div class="table-responsive">
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Additional Rate ($)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bedrooms as $bedroom)
                                <tr>
                                    <td>{{ $bedroom->name }}</td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control bedroom-rate"
                                            value="{{ $bedroom->rate }}" data-id="{{ $bedroom->id }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm save-bedroom"
                                            data-id="{{ $bedroom->id }}">Save</button>
                                        <button class="btn btn-danger btn-sm delete-bedroom"
                                            data-id="{{ $bedroom->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bathrooms -->
                <h3 class="sub-section-title">Bathrooms</h3>
                <div class="table-responsive">
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Additional Rate ($)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bathrooms as $bathroom)
                                <tr>
                                    <td>{{ $bathroom->name }}</td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control bathroom-rate"
                                            value="{{ $bathroom->rate }}" data-id="{{ $bathroom->id }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm save-bathroom"
                                            data-id="{{ $bathroom->id }}">Save</button>
                                        <button class="btn btn-danger btn-sm delete-bathroom"
                                            data-id="{{ $bathroom->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add-ons Section -->
            <div class="pricing-section card">
                <h2 class="section-title">Add-ons Pricing</h2>
                <div class="table-responsive">
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Additional Rate ($)</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customOptions as $option)
                                <tr>
                                    <td>{{ $option->name }}</td>
                                    <td>{{ $option->description }}</td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control addon-rate"
                                            value="{{ $option->rate }}" data-id="{{ $option->id }}">
                                    </td>
                                    <td>
                                        <div class="image-preview-container">
                                            <img src="{{ $option->image_path ? asset('storage/' . $option->image_path) : 'https://via.placeholder.com/50' }}"
                                                class="addon-image-preview" alt="{{ $option->name }}">
                                            <input type="file" class="form-control addon-image" accept="image/*"
                                                data-id="{{ $option->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm save-addon"
                                            data-id="{{ $option->id }}">Save</button>
                                        <button class="btn btn-danger btn-sm delete-addon"
                                            data-id="{{ $option->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons if needed
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Toast notification function
            function showToast(message, isSuccess = true) {
                const toast = document.createElement('div');
                toast.className = `toast ${isSuccess ? 'success' : 'error'}`;
                toast.textContent = message;
                document.body.appendChild(toast);

                // Show toast
                toast.style.display = 'block';

                // Hide after 3 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Generic save function with improved error handling
            async function saveData(url, data, successMessage, button) {
                const isFormData = data instanceof FormData;
                const options = {
                    method: isFormData ? 'POST' : 'PUT', // POST for FormData, PUT for regular data
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: isFormData ? data : new URLSearchParams(data)
                };

                // For FormData, let the browser set Content-Type with boundary
                if (!isFormData) {
                    options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
                }

                // For FormData with PUT method (Laravel expects this for updates)
                if (isFormData) {
                    data.append('_method', 'PUT'); // Laravel way to simulate PUT with FormData
                }

                try {
                    button.disabled = true;
                    button.classList.add('btn-saving');

                    const response = await fetch(url, options);
                    const responseData = await response.json();

                    if (!response.ok) {
                        throw responseData;
                    }

                    showToast(successMessage);

                    if (responseData.image_url) {
                        const imgPreview = button.closest('tr').querySelector('.addon-image-preview');
                        if (imgPreview) {
                            imgPreview.src = responseData.image_url + '?' + new Date().getTime();
                        }
                    }

                    return responseData;
                } catch (error) {
                    console.error('Error:', error);
                    let errorMessage = 'Error updating data';
                    if (error.message) {
                        errorMessage = error.message;
                    } else if (error.errors) {
                        errorMessage = Object.values(error.errors).join(', ');
                    }
                    showToast(errorMessage, false);
                    throw error;
                } finally {
                    button.disabled = false;
                    button.classList.remove('btn-saving');
                }
            }

            // Enhanced delete function
            async function deleteItem(url, button, successMessage) {
                try {
                    // Add loading state
                    button.disabled = true;
                    button.classList.add('btn-deleting');

                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to delete item');
                    }

                    showToast(successMessage);
                    return data;
                } catch (error) {
                    console.error('Delete error:', error);
                    showToast(error.message || 'Error deleting item', false);
                    throw error;
                } finally {
                    // Remove loading state
                    button.disabled = false;
                    button.classList.remove('btn-deleting');
                }
            }

            // Helper function to attach event listeners to save buttons
            function setupSaveHandler(selector, getDataFn, urlFn, successMessage) {
                document.querySelectorAll(selector).forEach(button => {
                    button.addEventListener('click', async function() {
                        const row = this.closest('tr');
                        const data = getDataFn(row, this);

                        if (data.error) {
                            showToast(data.error, false);
                            return;
                        }

                        const url = urlFn(this.dataset.id);
                        await saveData(url, data.payload, successMessage, this);
                    });
                });
            }

            // Helper function to attach event listeners to delete buttons
            function setupDeleteHandler(selector, urlFn, successMessage) {
                document.querySelectorAll(selector).forEach(button => {
                    button.addEventListener('click', async function() {
                        if (!confirm('Are you sure you want to delete this item?')) {
                            return;
                        }

                        const row = this.closest('tr');
                        const url = urlFn(this.dataset.id);

                        try {
                            const result = await deleteItem(url, this, successMessage);
                            if (result.success) {
                                row.remove();
                            }
                        } catch (error) {
                            console.error('Delete failed:', error);
                        }
                    });
                });
            }


            // Set up all save handlers
            setupSaveHandler(
                '.save-frequency',
                (row) => {
                    const discountRate = row.querySelector('.discount-rate').value;
                    if (!discountRate || discountRate < 0 || discountRate > 100) {
                        return {
                            error: 'Please enter a valid discount rate (0-100)'
                        };
                    }
                    return {
                        payload: {
                            discount_rate: discountRate
                        }
                    };
                },
                (id) => `/frequencies/${id}`,
                'Frequency updated successfully'
            );

            // Set up delete handlers for all tables
            setupDeleteHandler(
                '.delete-frequency',
                (id) => `/frequencies/${id}`,
                'Frequency deleted successfully'
            );

            setupDeleteHandler(
                '.delete-service-type',
                (id) => `/cleaning_types/${id}`,
                'Service type deleted successfully'
            );


            setupDeleteHandler(
                '.delete-size',
                (id) => `/square-footages/${id}`,
                'Square footage deleted successfully'
            );

            setupDeleteHandler(
                '.delete-bedroom',
                (id) => `/bedrooms/${id}`,
                'Bedroom deleted successfully'
            );

            setupDeleteHandler(
                '.delete-bathroom',
                (id) => `/bathrooms/${id}`,
                'Bathroom deleted successfully'
            );

            setupDeleteHandler(
                '.delete-addon',
                (id) => `/custom-options/${id}`,
                'Addon deleted successfully'
            );

            // Other save handlers
            setupSaveHandler(
                '.save-service-type',
                (row) => {
                    const rate = row.querySelector('.service-rate').value;

                    if (!rate || rate < 0) {
                        return {
                            error: 'Please enter a valid rate'
                        };
                    }

                    return {
                        payload: {
                            rate,
                        }
                    };
                },
                (id) => `/cleaning-types/${id}`,
                'Service type updated successfully'
            );

            setupSaveHandler(
                '.save-size',
                (row) => {
                    const rate = row.querySelector('.size-rate').value;
                    if (!rate || rate < 0) {
                        return {
                            error: 'Please enter a valid rate'
                        };
                    }
                    return {
                        payload: {
                            rate
                        }
                    };
                },
                (id) => `/square-footages/${id}`,
                'Square footage updated successfully'
            );

            setupSaveHandler(
                '.save-bedroom',
                (row) => {
                    const rate = row.querySelector('.bedroom-rate').value;
                    if (!rate || rate < 0) {
                        return {
                            error: 'Please enter a valid rate'
                        };
                    }
                    return {
                        payload: {
                            rate
                        }
                    };
                },
                (id) => `/bedrooms/${id}`,
                'Bedroom updated successfully'
            );

            setupSaveHandler(
                '.save-bathroom',
                (row) => {
                    const rate = row.querySelector('.bathroom-rate').value;
                    if (!rate || rate < 0) {
                        return {
                            error: 'Please enter a valid rate'
                        };
                    }
                    return {
                        payload: {
                            rate
                        }
                    };
                },
                (id) => `/bathrooms/${id}`,
                'Bathroom updated successfully'
            );

            setupSaveHandler(
                '.save-addon',
                (row) => {
                    const rate = row.querySelector('.addon-rate').value;
                    const imageInput = row.querySelector('.addon-image');

                    if (!rate || rate < 0) {
                        return {
                            error: 'Please enter a valid rate'
                        };
                    }

                    const formData = new FormData();
                    formData.append('rate', rate);

                    if (imageInput.files[0]) {
                        formData.append('image', imageInput.files[0]);
                    }

                    return {
                        payload: formData
                    };
                },
                (id) => `/custom-options/${id}`,
                'Addon updated successfully'
            );
            // Add visual feedback for required fields
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('input', function() {
                    const value = this.value;
                    if (value && value >= 0) {
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.add('is-invalid');
                    }
                });
            });

            // Image preview for addons
            document.querySelectorAll('.addon-image').forEach(input => {
                input.addEventListener('change', function() {
                    const preview = this.closest('tr').querySelector('.addon-image-preview');
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });

            console.log('Pricing management page loaded');
        });
    </script>

</body>

</html>
