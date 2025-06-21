<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 2rem;
        }

        .confirmation-card {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .confirmation-header {
            background: #28a745;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .confirmation-body {
            padding: 2rem;
            background: white;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.7rem;
        }
    </style>
</head>

<body>
    <div class="confirmation-card">
        <div class="confirmation-header">
            <i class="fas fa-check-circle fa-3x mb-3"></i>
            <h2>Booking Confirmed!</h2>
            <p class="mb-0">Thank you for your booking</p>
        </div>

        <div class="confirmation-body">
            <div class="alert alert-success">
                <i class="fas fa-check me-2"></i> Your booking has been successfully received
            </div>

            <div class="mb-4">
                <h4 class="mb-3">Booking Details</h4>

                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Booking ID:</strong><br>
                        #{{ $booking->id }}
                    </div>
                    <div class="col-6">
                        <strong>Status:</strong><br>
                        <span class="badge bg-success">Confirmed</span>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Service Date:</strong><br>
                    {{ optional($booking->service_date)->format('F j, Y') ?? 'Date not set' }} at
                    {{ $booking->service_time ?? 'Time not set' }}
                </div>

                <div class="mb-3">
                    <strong>Property Type:</strong><br>
                    {{ ucfirst($booking->property_type) }}
                </div>

                <div class="mb-3">
                    <strong>Total:</strong><br>
                    ${{ number_format($booking->total, 2) }}
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="/Booking_form" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i> Return Home
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="fas fa-print me-2"></i> Print Confirmation
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
