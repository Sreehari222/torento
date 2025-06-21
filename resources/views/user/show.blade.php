@extends('layouts.app')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('content')
    <div class="bookings-container animate-fade-in max-w-2xl mx-auto">
        <!-- Booking Details Card -->
        <div class="card shadow-lg rounded-xl overflow-hidden bg-white">
            <div class="card-header bg-gradient-to-r from-blue-50 to-indigo-50 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-semibold text-gray-800">Booking #{{ $booking->id }}</h3>
                    <a href="{{ route('admin.coupons.index') }}" class="text-blue-600 hover:text-blue-900 transition-colors"
                        data-tooltip="Back to Dashboard">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer</label>
                        <div class="mt-1 flex items-center">
                            <div
                                class="customer-avatar bg-gray-100 text-gray-600 font-semibold rounded-full w-8 h-8 flex items-center justify-center">
                                {{ substr($booking->customer_name, 0, 2) }}
                            </div>
                            <p class="ml-2 text-sm text-gray-900">{{ $booking->customer_name }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $booking->service }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                        <div class="mt-1 flex items-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-1 text-gray-400"></i>
                            <p class="text-sm text-gray-900">
                                {{ $booking->date_time ? $booking->date_time->format('M d, Y h:i A') : 'Not scheduled' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span
                            class="mt-1 px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ config('app.currency_symbol', '$') }}{{ number_format($booking->amount, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-gray-50 px-6 py-4 flex justify-end">
                <a href="{{ route('admin.coupons.index') }}" class="btn-secondary px-4 py-2">Back to Dashboard</a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

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

        .btn-secondary {
            @apply bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-all;
            @apply shadow-sm hover:shadow-md transform hover:-translate-y-0.5;
        }

        [data-tooltip] {
            position: relative;
        }

        [data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        [data-tooltip]:hover::after {
            opacity: 1;
            visibility: visible;
            bottom: calc(100% + 5px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (window.lucide) {
                lucide.createIcons();
            }

            // CSRF token check
            if (!document.querySelector('meta[name="csrf-token"]')) {
                console.error('CSRF token meta tag not found.');
                showToast('CSRF token missing. Please refresh the page.', 'error');
            }
        });

        function showToast(message, type = 'success') {
            // Replace with your toast library (e.g., Toastify, SweetAlert)
            console.log(`[${type}] ${message}`);
        }
    </script>
@endpush
