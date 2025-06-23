<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed | Your Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #2e8b57;  /* Sea Green */
            --primary-green-light: #3cb371; /* Medium Sea Green */
            --dark-green: #006400;  /* Dark Green */
            --black: #000000;
            --dark-gray: #333333;
            --light-gray: #f5f5f5;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-gray);
            color: var(--black);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .confirmation-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            width: 100%;
        }

        .confirmation-card {
            width: 100%;
            max-width: 100%;
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            min-height: 80vh;
        }

        .confirmation-hero {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .confirmation-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd' opacity='0.2'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .checkmark-circle {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            z-index: 1;
        }

        .confirmation-content {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            background-color: var(--white);
        }

        .confirmation-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--black);
        }

        .confirmation-subtitle {
            color: var(--dark-gray);
            margin-bottom: 2rem;
        }

        .details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            flex: 1;
        }

        .booking-details, .instructions-section {
            display: flex;
            flex-direction: column;
        }

        .detail-group {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            font-size: 0.875rem;
            color: var(--dark-gray);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1.125rem;
            font-weight: 500;
            color: var(--black);
        }

        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 1.5rem 0;
        }

        .action-buttons {
            display: grid;
            gap: 1rem;
            margin-top: 2rem;
            grid-column: span 2;
        }

        .btn-primary {
            background-color: var(--primary-green);
            border: none;
            padding: 0.75rem;
            font-weight: 500;
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--dark-green);
        }

        .btn-outline {
            border: 1px solid var(--dark-gray);
            color: var(--dark-gray);
            padding: 0.75rem;
            font-weight: 500;
        }

        .btn-outline:hover {
            background-color: var(--light-gray);
            border-color: var(--black);
            color: var(--black);
        }

        .status-badge {
            background-color: var(--primary-green);
            color: var(--white);
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .instructions-section {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .instructions-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--black);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instruction-item {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            align-items: flex-start;
        }

        .instruction-icon {
            color: var(--primary-green);
            margin-top: 0.2rem;
        }

        @media (max-width: 992px) {
            .details-container {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-column: span 1;
            }
        }

        @media (max-width: 768px) {
            .confirmation-card {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .confirmation-hero {
                padding: 2rem 1.5rem;
            }

            .confirmation-content {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="confirmation-container">
        <div class="confirmation-card">
            <div class="confirmation-hero">
                <div class="checkmark-circle">
                    <i class="fas fa-check fa-3x"></i>
                </div>
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; z-index: 1;">Success!</h1>
                <p style="font-size: 1.125rem; opacity: 0.9; z-index: 1;">Your booking is confirmed</p>
            </div>

            <div class="confirmation-content">
                <h2 class="confirmation-title">Booking Confirmation</h2>
                <p class="confirmation-subtitle">Your service details and preparation instructions</p>

                <div class="details-container">
                    <!-- Left Column - Booking Details -->
                    <div class="booking-details">
                        <div class="detail-group">
                            <div class="detail-label">Booking ID</div>
                            <div class="detail-value">#{{ $booking->id }}</div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-label">Status</div>
                            <div class="status-badge">
                                <i class="fas fa-check-circle"></i>
                                <span>Confirmed</span>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="detail-group">
                            <div class="detail-label">Service Date & Time</div>
                            <div class="detail-value">
                                <div><i class="far fa-calendar-alt me-2"></i>{{ optional($booking->service_date)->format('l, F j, Y') ?? 'Date not set' }}</div>
                                <div class="mt-1"><i class="far fa-clock me-2"></i>{{ $booking->service_time ?? 'Time not set' }}</div>
                            </div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-label">Property Type</div>
                            <div class="detail-value"><i class="fas fa-home me-2"></i>{{ ucfirst($booking->property_type) }}</div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-label">Total Amount</div>
                            <div class="detail-value"><i class="fas fa-dollar-sign me-2"></i>${{ number_format($booking->total, 2) }}</div>
                        </div>
                    </div>

                    <!-- Right Column - Instructions -->
                    <div class="instructions-section">
                        <h3 class="instructions-title">
                            <i class="fas fa-clipboard-check"></i> Preparation Instructions
                        </h3>
                        <div class="instruction-item">
                            <i class="fas fa-check-circle instruction-icon"></i>
                            <div><strong>Access:</strong> Ensure property is accessible at scheduled time</div>
                        </div>
                        <div class="instruction-item">
                            <i class="fas fa-check-circle instruction-icon"></i>
                            <div><strong>Preparation:</strong> Clear work area of obstacles</div>
                        </div>
                        <div class="instruction-item">
                            <i class="fas fa-check-circle instruction-icon"></i>
                            <div><strong>Notification:</strong> We'll call 30 minutes before arrival</div>
                        </div>
                        <div class="instruction-item">
                            <i class="fas fa-check-circle instruction-icon"></i>
                            <div><strong>Payment:</strong> Have payment ready if not prepaid</div>
                        </div>
                        <div class="instruction-item">
                            <i class="fas fa-check-circle instruction-icon"></i>
                            <div><strong>Changes:</strong> Contact us 24hrs in advance for changes</div>
                        </div>
                        <div class="instruction-item">
                            <i class="fas fa-check-circle instruction-icon"></i>
                            <div><strong>Emergency:</strong> Call (555) 123-4567 for urgent issues</div>
                        </div>
                    </div>

                    <!-- Full Width Action Buttons -->
                    <div class="action-buttons">
                        <a href="/Booking_form" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Return to Homepage
                        </a>
                        <button onclick="window.print()" class="btn btn-outline">
                            <i class="fas fa-print me-2"></i> Print Confirmation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
