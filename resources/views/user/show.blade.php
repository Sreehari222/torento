@extends('layouts.app')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('content')
    <div class="booking-container">
        <div class="booking-card">
            <!-- Header -->
            <div class="booking-header">
                <div class="header-content">
                    <h2>Booking #{{ $booking->id }}</h2>
                </div>
                <p class="booking-date">
                    Created on {{ $booking->created_at ? $booking->created_at->format('M d, Y') : 'Date not available' }}
                </p>
            </div>

            <!-- Main Content -->
            <div class="booking-body">
                <div class="grid-section">
                    <!-- Customer Information -->
                    <div class="info-section">
                        <h3 class="section-title">Customer Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="info-label">Full Name</p>
                                <p class="info-value">{{ $booking->first_name }} {{ $booking->last_name }}</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Contact Information</p>
                                <p class="info-value">{{ $booking->email }}</p>
                                <p class="info-value">{{ $booking->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Property Address</p>
                                <p class="info-value">
                                    {{ $booking->address }}{{ $booking->suite ? ', Apt ' . $booking->suite : '' }}<br>
                                    {{ $booking->city }}, {{ $booking->area }}<br>
                                    {{ $booking->postal_code }}
                                </p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Property Type</p>
                                <p class="info-value">{{ ucfirst($booking->property_type ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Information -->
                    <div class="info-section">
                        <h3 class="section-title">Service Details</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="info-label">Service Date & Time</p>
                                <p class="info-value">
                                    {{ $booking->service_date ? \Carbon\Carbon::parse($booking->service_date)->format('M d, Y') : 'N/A' }}
                                    at
                                    {{ $booking->service_time ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Service Frequency</p>
                                <p class="info-value">{{ $booking->frequency->name ?? 'N/A' }}</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Cleaning Type</p>
                                <p class="info-value">{{ $booking->cleaningType->name ?? 'N/A' }}</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Property Details</p>
                                <p class="info-value">
                                    {{ $booking->square_footage_id ? $booking->squareFootage->name . ' sq ft' : 'N/A' }}<br>
                                    {{ $booking->bedrooms_id ? $booking->bedrooms->name . ' bedrooms' : 'N/A' }}<br>
                                    {{ $booking->bathrooms_id ? $booking->bathrooms->name . ' bathrooms' : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="additional-info">
                    <h3 class="section-title">Additional Information</h3>
                    <div class="info-grid cols-2">
                        <div class="info-column">
                            <div class="info-item">
                                <p class="info-label">Cleaning Instructions</p>
                                <p class="info-value">{{ $booking->cleaning_instructions ?? 'None provided' }}</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Access Information</p>
                                <p class="info-value">{{ $booking->access_info ?? 'None provided' }}</p>
                            </div>
                        </div>
                        <div class="info-column">
                            <div class="info-item">
                                <p class="info-label">Parking Information</p>
                                <p class="info-value">{{ $booking->parking ?? 'None provided' }}</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Terms Accepted</p>
                                <p class="info-value">{{ $booking->terms_accepted ? 'Yes' : 'No' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="payment-section">
                    <h3 class="section-title">Payment Summary</h3>
                    <div class="info-grid cols-2">
                        <div class="info-item">
                            <p class="info-label">Payment Method</p>
                            <p class="info-value">{{ ucfirst($booking->payment_method ?? 'N/A') }}</p>
                        </div>
                        <div class="info-item">
                            <p class="info-label">Coupon Code</p>
                            <p class="info-value">{{ $booking->coupon_code ?? 'None used' }}</p>
                        </div>
                    </div>
                    <div class="payment-details">
                        <div class="payment-row">
                            <span>Subtotal:</span>
                            <span>${{ number_format($booking->subtotal ?? 0, 2) }}</span>
                        </div>
                        <div class="payment-row">
                            <span>Discount:</span>
                            <span class="discount">-${{ number_format($booking->discount_amount ?? 0, 2) }}</span>
                        </div>
                        <div class="payment-row total">
                            <span>Total:</span>
                            <span>${{ number_format($booking->total ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <!-- Footer Actions -->
            <div class="booking-actions">
                <a href="{{ route('admin.bookings.index') }}" class="btn back-btn">Back to List</a>
                @auth
                    @if (auth()->user()->hasPermissionTo('delete bookings'))
                        <form action="{{ route('bookings.delete', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn delete-btn"
                                onclick="return confirm('Are you sure you want to delete this booking?')">
                                Delete Booking
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Base Styles */
        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
            animation: fadeIn 0.5s ease-out;
        }

        .booking-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        /* Header Styles */
        .booking-header {
            background: linear-gradient(135deg, #52c43b 0%, #fff479 100%);
            padding: 1.5rem 2rem;
            color: white;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .booking-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }

        .booking-date {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            margin: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.confirmed {
            background-color: #10b981;
        }

        .status-badge.pending {
            background-color: #f59e0b;
            animation: pulse 2s infinite;
        }

        .status-badge.cancelled {
            background-color: #ef4444;
        }

        /* Body Styles */
        .booking-body {
            padding: 2rem;
        }

        .grid-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .grid-section {
                grid-template-columns: 1fr 1fr;
            }
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: #3b82f6;
        }

        /* Information Grid */
        .info-grid {
            display: grid;
            gap: 1.5rem;
        }

        .info-grid.cols-2 {
            grid-template-columns: 1fr;
        }

        @media (min-width: 640px) {
            .info-grid.cols-2 {
                grid-template-columns: 1fr 1fr;
            }
        }

        .info-item {
            margin-bottom: 0.5rem;
        }

        .info-label {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #111827;
            font-weight: 500;
            line-height: 1.6;
        }

        /* Additional Information */
        .additional-info {
            margin: 2.5rem 0;
        }

        /* Payment Section */
        .payment-section {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .payment-details {
            margin-top: 1.5rem;
            padding-top: 1rem;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }

        .payment-row.total {
            font-weight: 600;
            font-size: 1.1rem;
            border-top: 1px dashed #d1d5db;
            padding-top: 1rem;
            margin-top: 0.5rem;
            color: #1f2937;
        }

        .discount {
            color: #ef4444;
        }

        /* Action Buttons */
        .booking-actions {
            background-color: #f9fafb;
            padding: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.75rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            cursor: pointer;
            text-align: center;
        }

        .back-btn {
            border: 1px solid #d1d5db;
            color: #374151;
            background: white;
        }

        .back-btn:hover {
            background-color: #f3f4f6;
        }

        .delete-btn {
            background-color: #ef4444;
            color: white;
            border: none;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .booking-header {
                padding: 1.25rem;
            }

            .booking-body {
                padding: 1.5rem;
            }

            .section-title {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush
