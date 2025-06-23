@extends('layouts.app')

@section('title', 'Coupon Management')
@section('page-title', 'Coupon Dashboard')

@section('top-bar-actions')
    <button class="btn-create" id="create-coupon-btn">
        <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
        Create Coupon
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <p class="stat-card-title">Total Coupons</p>
                <div class="stat-card-icon" style="background-color: #dbeafe;">
                    <i data-lucide="ticket" style="color: #2563eb; width: 20px; height: 20px;"></i>
                </div>
            </div>
            <p class="stat-card-value">{{ $totalCoupons }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <p class="stat-card-title">Active Coupons</p>
                <div class="stat-card-icon" style="background-color: #dcfce7;">
                    <i data-lucide="check-circle" style="color: #16a34a; width: 20px; height: 20px;"></i>
                </div>
            </div>
            <p class="stat-card-value">{{ $activeCoupons }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <p class="stat-card-title">Total Bookings</p>
                <div class="stat-card-icon" style="background-color: #e0f2fe;">
                    <i data-lucide="calendar" style="color: #0369a1; width: 20px; height: 20px;"></i>
                </div>
            </div>
            <p class="stat-card-value">{{ $totalBookings }}</p>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="table-card">
    <div class="table-header">
        <h3>Recent Bookings</h3>
    </div>
    <div class="table-responsive">
        <table id="bookings-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentBookings as $booking)
                    <tr data-status="{{ strtolower($booking->status) }}" id="booking-row-{{ $booking->id }}">
                        <td>#{{ $booking->id }}</td>
                        <td>
                            <div class="customer-cell">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($booking->user->name ?? '', 0, 1)) }}
                                </div>
                                <div class="customer-info">
                                    <div class="customer-name">{{ $booking->name ?? 'N/A' }}</div>
                                    <div class="customer-email">{{ $booking->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $booking->service->name ?? 'N/A' }}</td>
                        <td>
                            <div class="datetime-cell">
                                <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                                {{ $booking->created_at->format('M j, Y') }}
                                <i data-lucide="clock" style="width: 14px; height: 14px; margin-left: 8px;"></i>
                                {{ $booking->created_at->format('h:i A') }}
                            </div>
                        </td>
                        <td>${{ number_format($booking->total ?? 0, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($booking->payment_method) }}">
                                {{ ucfirst($booking->payment_method) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-booking"
                                    data-booking-id="{{ $booking->id }}"
                                    data-csrf-token="{{ csrf_token() }}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
    <!-- Create Coupon Modal -->
    <div class="modal-overlay" id="coupon-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create New Coupon</h3>
                <button type="button" class="close-modal" id="close-coupon-modal">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="coupon-form" action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="code">Coupon Code</label>
                    <div class="input-group">
                        <input type="text" name="code" id="code" class="form-control" placeholder="e.g. SUMMER20"
                            value="{{ old('code') }}" required>
                        <button type="button" id="generate-code" class="btn-generate">
                            <i data-lucide="dice-5"></i> Generate
                        </button>
                    </div>
                    <small class="form-hint">Uppercase letters and numbers only (6-12 characters)</small>
                    <div class="error-message" id="code-error"></div>
                </div>
                <div class="form-group">
                    <label for="discount_type">Discount Type</label>
                    <select name="discount_type" id="discount-type" class="form-control">
                        <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                            Percentage
                        </option>
                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>
                            Fixed Amount
                        </option>
                    </select>
                    <div class="error-message" id="discount_type-error"></div>
                </div>
                <div class="form-group">
                    <label for="discount_value">Discount Value</label>
                    <div class="input-group">
                        <input type="number" name="discount_value" id="discount_value" class="form-control"
                            placeholder="e.g. 20" step="0.01" min="0" value="{{ old('discount_value') }}"
                            required>
                        <span class="input-suffix" id="discount-suffix">%</span>
                    </div>
                    <div class="error-message" id="discount_value-error"></div>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                        min="{{ date('Y-m-d') }}" value="{{ old('expiry_date') }}">
                    <small class="form-hint">Leave blank for no expiration</small>
                    <div class="error-message" id="expiry_date-error"></div>
                </div>
                <div class="form-group">
                    <label for="is_active">Active Status</label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="toggle-label"></label>
                        <span class="toggle-text">{{ old('is_active', true) ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="error-message" id="is_active-error"></div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancel-coupon-btn">Cancel</button>
                    <button type="submit" class="btn-primary">Create Coupon</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Deletion</h3>
                <button type="button" class="close-modal" id="close-delete-modal">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this coupon? This action cannot be undone.</p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="cancel-delete-btn">Cancel</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">Delete Coupon</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Dashboard Specific Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-card-title {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-card-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        /* Table Styles */
        .table-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box i {
            position: absolute;
            left: 0.75rem;
            color: #9ca3af;
        }

        .search-box input {
            padding: 0.5rem 0.75rem 0.5rem 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            min-width: 200px;
        }

        #status-filter {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f9fafb;
        }

        th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }

        .code-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .code {
            font-family: monospace;
            font-weight: 500;
        }

        .copy-code {
            cursor: pointer;
            color: #4f46e5;
            display: flex;
            align-items: center;
        }

        .copy-code:hover {
            color: #4338ca;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-expired {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete {
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-edit {
            background-color: #e0e7ff;
            color: #4f46e5;
        }

        .btn-edit:hover {
            background-color: #c7d2fe;
        }

        .btn-delete {
            background-color: #fee2e2;
            color: #ef4444;
        }

        .btn-delete:hover {
            background-color: #fecaca;
        }

        .table-footer {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: center;
            border-top: 1px solid #e5e7eb;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 1rem;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
        }

        .close-modal {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            color: #6b7280;
            transition: background-color 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .close-modal:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-body p {
            margin: 0;
            color: #6b7280;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
            padding: 0 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
        }

        .input-group {
            position: relative;
            display: flex;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        .input-suffix {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.875rem;
            color: #6b7280;
        }

        .btn-generate {
            margin-left: 0.5rem;
            padding: 0.5rem 0.75rem;
            background-color: #e0e7ff;
            color: #4f46e5;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: background-color 0.2s ease;
        }

        .btn-generate:hover {
            background-color: #c7d2fe;
        }

        .form-hint {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            min-height: 1rem;
        }

        /* Toggle Switch */
        .toggle-switch {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .toggle-switch input[type="checkbox"] {
            position: absolute;
            opacity: 0;
        }

        .toggle-label {
            display: inline-block;
            width: 2.75rem;
            height: 1.5rem;
            background-color: #e5e7eb;
            border-radius: 9999px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .toggle-label::after {
            content: '';
            position: absolute;
            width: 1.25rem;
            height: 1.25rem;
            background-color: white;
            border-radius: 9999px;
            top: 0.125rem;
            left: 0.125rem;
            transition: transform 0.2s ease;
        }

        .toggle-switch input[type="checkbox"]:checked+.toggle-label {
            background-color: #4f46e5;
        }

        .toggle-switch input[type="checkbox"]:checked+.toggle-label::after {
            transform: translateX(1.25rem);
        }

        .toggle-text {
            font-size: 0.875rem;
            color: #111827;
        }

        /* Modal Actions */
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-secondary {
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            color: #111827;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .btn-primary {
            padding: 0.5rem 1rem;
            background-color: #4f46e5;
            color: white;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-danger {
            padding: 0.5rem 1rem;
            background-color: #ef4444;
            color: white;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .pagination a {
            color: #4f46e5;
            text-decoration: none;
            background-color: #e0e7ff;
        }

        .pagination a:hover {
            background-color: #c7d2fe;
        }

        .pagination .active span {
            background-color: #4f46e5;
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .table-actions {
                width: 100%;
                flex-direction: column;
                gap: 0.75rem;
            }

            .search-box,
            #status-filter {
                width: 100%;
            }

            th,
            td {
                padding: 0.75rem 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            lucide.createIcons();

            // ========== Coupon Modal Functionality ==========
            const createCouponBtn = document.getElementById('create-coupon-btn');
            const couponModal = document.getElementById('coupon-modal');
            const cancelCouponBtn = document.getElementById('cancel-coupon-btn');
            const closeCouponModal = document.getElementById('close-coupon-modal');
            const couponForm = document.getElementById('coupon-form');
            const discountType = document.getElementById('discount-type');
            const discountSuffix = document.getElementById('discount-suffix');
            const isActiveToggle = document.getElementById('is_active');
            const toggleText = document.querySelector('.toggle-text');
            const generateCodeBtn = document.getElementById('generate-code');

            // Toggle modal visibility
            function toggleModal(modal, show) {
                if (show) {
                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden';
                } else {
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                    if (modal === couponModal) clearErrors();
                }
            }

            // Generate random coupon code
            function generateCouponCode() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let result = '';
                for (let i = 0; i < 8; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.getElementById('code').value = result;
            }

            // Toggle discount suffix between % and $
            function updateDiscountSuffix() {
                discountSuffix.textContent = discountType.value === 'percentage' ? '%' : '$';
            }

            // Update toggle switch text
            function updateToggleText() {
                toggleText.textContent = isActiveToggle.checked ? 'Active' : 'Inactive';
            }

            // Clear all error messages
            function clearErrors() {
                document.querySelectorAll('.error-message').forEach(el => {
                    el.textContent = '';
                });
            }

            // Validate coupon code format (uppercase letters and numbers)
            function validateCouponCode(code) {
                return /^[A-Z0-9]{6,12}$/.test(code);
            }

            // Validate discount value based on type
            function validateDiscountValue(value, type) {
                const numValue = parseFloat(value);
                if (isNaN(numValue)) return false;

                if (type === 'percentage') {
                    return numValue > 0 && numValue <= 100;
                } else {
                    return numValue > 0;
                }
            }

            // Validate expiry date (if provided)
            function validateExpiryDate(dateString) {
                if (!dateString) return true;
                const expiryDate = new Date(dateString);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                return expiryDate >= today;
            }

            // Handle form submission
            async function handleFormSubmit(e) {
                e.preventDefault();
                clearErrors();

                const formData = new FormData(couponForm);
                const errors = {};

                // Validate coupon code
                if (!formData.get('code') || !validateCouponCode(formData.get('code'))) {
                    errors.code = 'Please enter a valid coupon code (6-12 uppercase letters and numbers)';
                }

                // Validate discount value
                if (!formData.get('discount_value') || !validateDiscountValue(
                        formData.get('discount_value'),
                        formData.get('discount_type')
                    )) {
                    const type = formData.get('discount_type');
                    errors.discount_value = type === 'percentage' ?
                        'Please enter a valid percentage (1-100)' :
                        'Please enter a valid amount (greater than 0)';
                }

                // Validate expiry date
                if (formData.get('expiry_date') && !validateExpiryDate(formData.get('expiry_date'))) {
                    errors.expiry_date = 'Expiry date must be today or in the future';
                }

                // Display errors if any
                if (Object.keys(errors).length > 0) {
                    for (const [field, message] of Object.entries(errors)) {
                        const errorElement = document.getElementById(`${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = message;
                        }
                    }
                    return;
                }

                try {
                    const response = await fetch(couponForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Success - reload the page
                        window.location.reload();
                    } else if (data.errors) {
                        // Show validation errors from server
                        for (const [field, fieldErrors] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = fieldErrors[0];
                            }
                        }
                    } else {
                        // Generic error message
                        alert('An error occurred. Please try again.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please check your connection and try again.');
                }
            }

            // ========== Delete Modal Functionality ==========
            const deleteModal = document.getElementById('delete-modal');
            const closeDeleteModal = document.getElementById('close-delete-modal');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
            const deleteForm = document.getElementById('delete-form');
            let deleteButtons = document.querySelectorAll('.btn-delete');

            // Set up delete buttons
            function setupDeleteButtons() {
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const couponId = this.getAttribute('data-id');
                        deleteForm.action = `/admin/coupons/${couponId}`;
                        toggleModal(deleteModal, true);
                    });
                });
            }

            // ========== Table Functionality ==========
            const statusFilter = document.getElementById('status-filter');
            const couponSearch = document.getElementById('coupon-search');
            const copyButtons = document.querySelectorAll('.copy-code');

            // Filter table by status
            function filterTableByStatus() {
                const status = statusFilter.value;
                document.querySelectorAll('table tbody tr').forEach(row => {
                    if (status === 'all' || row.getAttribute('data-status') === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Search coupons
            function searchCoupons() {
                const searchTerm = couponSearch.value.toLowerCase();
                document.querySelectorAll('table tbody tr').forEach(row => {
                    const code = row.querySelector('.code').textContent.toLowerCase();
                    if (code.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Copy coupon code to clipboard
            function setupCopyButtons() {
                copyButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const code = this.getAttribute('data-code');
                        navigator.clipboard.writeText(code).then(() => {
                            const icon = this.querySelector('i');
                            const originalIcon = icon.getAttribute('data-lucide');

                            // Change icon to checkmark temporarily
                            icon.setAttribute('data-lucide', 'check');
                            lucide.createIcons();

                            // Revert after 2 seconds
                            setTimeout(() => {
                                icon.setAttribute('data-lucide', originalIcon);
                                lucide.createIcons();
                            }, 2000);
                        });
                    });
                });
            }

            // ========== Event Listeners ==========
            // Modal controls
            createCouponBtn.addEventListener('click', () => toggleModal(couponModal, true));
            cancelCouponBtn.addEventListener('click', () => toggleModal(couponModal, false));
            closeCouponModal.addEventListener('click', () => toggleModal(couponModal, false));
            couponModal.addEventListener('click', (e) => {
                if (e.target === couponModal) toggleModal(couponModal, false);
            });

            // Delete modal controls
            closeDeleteModal.addEventListener('click', () => toggleModal(deleteModal, false));
            cancelDeleteBtn.addEventListener('click', () => toggleModal(deleteModal, false));
            deleteModal.addEventListener('click', (e) => {
                if (e.target === deleteModal) toggleModal(deleteModal, false);
            });

            // Form elements
            discountType.addEventListener('change', updateDiscountSuffix);
            isActiveToggle.addEventListener('change', updateToggleText);
            generateCodeBtn.addEventListener('click', generateCouponCode);

            // Form submission
            couponForm.addEventListener('submit', handleFormSubmit);

            // Table controls
            statusFilter.addEventListener('change', filterTableByStatus);
            couponSearch.addEventListener('input', searchCoupons);

            // Close modals with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (couponModal.classList.contains('show')) toggleModal(couponModal, false);
                    if (deleteModal.classList.contains('show')) toggleModal(deleteModal, false);
                }
            });

            // ========== Initialize ==========
            updateDiscountSuffix();
            updateToggleText();
            setupDeleteButtons();
            setupCopyButtons();
        });


        document.addEventListener('DOMContentLoaded', function() {
    // Add click event for all delete buttons
    document.querySelectorAll('.delete-booking').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const bookingId = this.getAttribute('data-booking-id');
            const csrfToken = this.getAttribute('data-csrf-token');

            if (confirm('Are you sure you want to delete this booking?')) {
                fetch(`/bookings/${bookingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Remove the row from the table
                        document.getElementById(`booking-row-${bookingId}`).remove();
                        // Optionally show a success message
                        alert('Booking deleted successfully');
                    } else {
                        throw new Error('Failed to delete booking');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting booking');
                });
            }
        });
    });
});
    </script>
@endsection
