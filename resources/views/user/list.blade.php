@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Bookings</h1>
                    <div>
                        <a href="{{ route('showform') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Booking
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Bookings</h5>
                        <div class="d-flex">
                            <input type="text" id="booking-search" class="form-control form-control-sm" placeholder="Search bookings...">
                            <select id="status-filter" class="form-select form-select-sm ms-2" style="width: 120px;">
                                <option value="">All Statuses</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                        <th>Service Details</th>
                                        <th>Property</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                        <tr data-status="{{ $booking->status }}">
                                            <td class="fw-bold">#{{ $booking->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title bg-light rounded-circle">
                                                            {{ strtoupper(substr($booking->first_name, 0, 1)) }}{{ strtoupper(substr($booking->last_name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $booking->first_name }} {{ $booking->last_name }}</strong><br>
                                                        <small class="text-muted">{{ $booking->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fas fa-phone-alt me-1 text-muted"></i> {{ $booking->phone }}<br>
                                                <i class="fas fa-key me-1 text-muted"></i> {{ $booking->access_info ?? 'None' }}
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt me-1 text-muted"></i> {{ $booking->address }} {{ $booking->suite }}<br>
                                                {{ $booking->city }}, {{ $booking->area }}<br>
                                                {{ $booking->postal_code }}<br>
                                                <i class="fas fa-car me-1 text-muted"></i> {{ $booking->parking ?? 'No parking' }}
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span><i class="far fa-calendar-alt me-1 text-muted"></i> {{ $booking->service_date->format('M d, Y') }}</span>
                                                    <span><i class="far fa-clock me-1 text-muted"></i> {{ $booking->service_time }}</span>
                                                    <span><i class="fas fa-sync-alt me-1 text-muted"></i> {{ $booking->frequency->name ?? 'N/A' }}</span>
                                                    <span><i class="fas fa-broom me-1 text-muted"></i> {{ $booking->cleaningType->name ?? 'N/A' }}</span>
                                                    <span><i class="fas fa-ruler-combined me-1 text-muted"></i> {{ $booking->squareFootage->name ?? 'N/A' }}</span>
                                                    <span><i class="fas fa-bed me-1 text-muted"></i> / <i class="fas fa-bath me-1 text-muted"></i> {{ $booking->bedrooms->name ?? 'N/A' }} / {{ $booking->bathrooms->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark mb-1">
                                                    {{ ucfirst($booking->property_type) }}
                                                </span>
                                                <p class="mb-0">{{ Str::limit($booking->cleaning_instructions, 50) ?? 'No instructions' }}</p>
                                            </td>
                                            <td class="payment-info">
                                                <span class="badge bg-light text-dark mb-1">
                                                    {{ ucfirst($booking->payment_method) }}
                                                </span>
                                                <div class="d-flex flex-column">
                                                    <small>Subtotal: ${{ number_format($booking->subtotal, 2) }}</small>
                                                    <small>Discount: ${{ number_format($booking->discount_amount, 2) }}</small>
                                                    @if($booking->coupon_code)
                                                        <small>Coupon: {{ $booking->coupon_code }} (-${{ number_format($booking->coupon_discount, 2) }})</small>
                                                    @endif
                                                    <strong>Total: ${{ number_format($booking->total, 2) }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status_color }} rounded-pill">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-2">
                                                    <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                       class="btn btn-sm btn-info d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No bookings found</h5>
                                                    <a href="{{ route('showform') }}" class="btn btn-primary mt-2">
                                                        <i class="fas fa-plus me-1"></i> Create Booking
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Base Styles */
    .container {
        padding: 2rem 1rem;
        max-width: 100%;
    }

    /* Header Styles */
    h1 {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
        font-size: 2rem;
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
        white-space: nowrap;
    }

    .table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: top;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Status Badges */
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .bg-confirmed {
        background-color: #10b981 !important;
    }

    .bg-pending {
        background-color: #f59e0b !important;
        color: #1e293b !important;
    }

    .bg-cancelled {
        background-color: #ef4444 !important;
    }

    .bg-completed {
        background-color: #3b82f6 !important;
    }

    /* Action Buttons */
    .btn {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        min-width: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .btn-primary {
        background-color: #6366f1;
        border-color: #6366f1;
    }

    .btn-primary:hover {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    .btn-info {
        background-color: #0ea5e9;
        border-color: #0ea5e9;
    }

    .btn-info:hover {
        background-color: #0284c7;
        border-color: #0284c7;
    }

    .btn-warning {
        background-color: #f59e0b;
        border-color: #f59e0b;
        color: #1e293b;
    }

    .btn-warning:hover {
        background-color: #d97706;
        border-color: #d97706;
    }

    .btn-danger {
        background-color: #ef4444;
        border-color: #ef4444;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        border-color: #dc2626;
    }

    /* Alert Styles */
    .alert {
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
    }

    /* Avatar */
    .avatar-sm {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-title {
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Hover Effects */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Empty State */
    .text-center {
        color: #64748b;
        padding: 2rem;
    }

    /* Payment Information */
    .payment-info {
        white-space: nowrap;
    }

    /* Form Controls */
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #e2e8f0;
    }

    /* Pagination */
    .pagination {
        margin-bottom: 0;
    }

    .page-item.active .page-link {
        background-color: #6366f1;
        border-color: #6366f1;
    }

    .page-link {
        color: #6366f1;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 1.5rem;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
        }

        .table td, .table th {
            padding: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .btn {
            font-size: 0.65rem;
            padding: 0.35rem 0.5rem;
            min-width: auto;
        }

        .table td, .table th {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .avatar-sm {
            width: 28px;
            height: 28px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make table rows clickable
        document.querySelectorAll('.table tbody tr[data-status]').forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicking on buttons, links, or form elements
                if (!e.target.closest('a, button, input, select, textarea, form')) {
                    window.location = this.querySelector('a.btn-info').href;
                }
            });
            row.style.cursor = 'pointer';
        });

        // Search functionality
        const searchInput = document.getElementById('booking-search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.table tbody tr[data-status]').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Status filter
        const statusFilter = document.getElementById('status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function(e) {
                const filterValue = e.target.value;
                document.querySelectorAll('.table tbody tr[data-status]').forEach(row => {
                    if (filterValue === '') {
                        row.style.display = '';
                    } else {
                        const rowStatus = row.getAttribute('data-status');
                        row.style.display = rowStatus === filterValue ? '' : 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
