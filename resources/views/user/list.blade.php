@extends('layouts.app')

@section('title', 'Bookings Management')
@section('page-title', 'Bookings Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Bookings</h1>
                    <a href="{{ route('showform') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> New Booking
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">All Bookings</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
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
                                <tr>
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
                                        <i class="fas fa-map-marker-alt me-1 text-muted"></i> {{ $booking->address }}
                                        {{ $booking->suite }}<br>
                                        {{ $booking->city }}, {{ $booking->area }}<br>
                                        {{ $booking->postal_code }}<br>
                                        <i class="fas fa-car me-1 text-muted"></i> {{ $booking->parking ?? 'No parking' }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><i class="far fa-calendar-alt me-1 text-muted"></i>
                                                {{ $booking->service_date->format('M d, Y') }}</span>
                                            <span><i class="far fa-clock me-1 text-muted"></i>
                                                {{ $booking->service_time }}</span>
                                            <span><i class="fas fa-sync-alt me-1 text-muted"></i>
                                                {{ $booking->frequency->name ?? 'N/A' }}</span>
                                            <span><i class="fas fa-broom me-1 text-muted"></i>
                                                {{ $booking->cleaningType->name ?? 'N/A' }}</span>
                                            <span><i class="fas fa-ruler-combined me-1 text-muted"></i>
                                                {{ $booking->squareFootage->name ?? 'N/A' }}</span>
                                            <span><i class="fas fa-bed me-1 text-muted"></i> / <i
                                                    class="fas fa-bath me-1 text-muted"></i>
                                                {{ $booking->bedrooms->name ?? 'N/A' }} /
                                                {{ $booking->bathrooms->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark mb-1">
                                            {{ ucfirst($booking->property_type) }}
                                        </span>
                                        <p class="mb-0">
                                            {{ Str::limit($booking->cleaning_instructions, 50) ?? 'No instructions' }}</p>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark mb-1">
                                            {{ ucfirst($booking->payment_method) }}
                                        </span>
                                        <div class="d-flex flex-column">
                                            <small>Subtotal: ${{ number_format($booking->subtotal, 2) }}</small>
                                            <small>Discount: ${{ number_format($booking->discount_amount, 2) }}</small>
                                            @if ($booking->coupon_code)
                                                <small>Coupon: {{ $booking->coupon_code }}
                                                    (-${{ number_format($booking->coupon_discount, 2) }})</small>
                                            @endif
                                            <strong>Total: ${{ number_format($booking->total, 2) }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $booking->status_color }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('showdetails', $booking->id) }}"
                                                class="btn btn-sm btn-info">
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
@endsection

@push('styles')
    <style>
        /* Custom styles to enhance the Bootstrap layout */
        .avatar-sm {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-title {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .badge {
            font-weight: 500;
        }

        /* Status badge colors */
        .bg-pending {
            background-color: #f59e0b !important;
        }

        .bg-confirmed {
            background-color: #10b981 !important;
        }

        .bg-completed {
            background-color: #3b82f6 !important;
        }

        .bg-cancelled {
            background-color: #ef4444 !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- If you have any custom JavaScript, include it here -->
    <script>
        // Any custom JavaScript can go here
        document.addEventListener('DOMContentLoaded', function() {
            // Your custom JS code
        });
    </script>

