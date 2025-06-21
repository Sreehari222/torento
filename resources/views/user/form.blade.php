<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTA Professional Cleaning Services | Book Online</title>
    <meta name="description"
        content="Professional cleaning services serving all GTA areas including Toronto, Mississauga, Brampton, Vaughan, Markham, Richmond Hill, and more. Book your residential or commercial cleaning online.">
    <meta name="keywords"
        content="cleaning services, GTA cleaning, Toronto cleaners, Mississauga cleaning, Brampton cleaners, Vaughan cleaning, Markham cleaning, Richmond Hill cleaning, house cleaning, commercial cleaning, move in cleaning, Airbnb cleaning">
    <link rel="stylesheet" href="{{ asset('css/step_form.css') }}">
    <script>
        // Route helper function
        window.route = function(name, params = {}) {
            const routes = {
                'validate.coupon': '/validate-coupon',
                // Add other routes as needed
            };

            if (!routes[name]) {
                console.error('Route not found:', name);
                return '#';
            }

            return routes[name];
        };
    </script>
    <script src="{{ asset('js/booking-form.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Error message styling */
        #form-errors {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 0.25rem;
            color: #721c24;
            display: none;
        }

        #form-errors ul {
            margin-bottom: 0;
            padding-left: 1rem;
        }

        .coupon-message {
            display: none;
            /* hidden by default */
            margin-top: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }


        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        /* Add to your stylesheet */
        #coupon-message {
            display: none;
            font-size: 0.9rem;
            margin-top: 10px;
            padding: 8px 12px;
            border-radius: 4px;
        }

        #coupon-message.success {
            color: #22c55e;
            background-color: rgba(34, 197, 94, 0.1);
        }

        #coupon-message.error {
            color: #ef4444;
            background-color: rgba(239, 68, 68, 0.1);
        }

        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hidden-by-package {
            display: none !important;
        }

        .postal-code-hint {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="animated-bg"></div>
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    <div class="container">
        <!-- Form Section -->
        <div class="form-section">
            <h1>Book Your GTA Cleaning Service</h1>
            <form action="{{ route('booking.store') }}" method="POST" id="booking-form">
                @csrf
                <!-- General error container -->
                <div id="form-errors"></div>

                <!-- Honeypot field for spam prevention -->
                <input type="text" name="honeypot" style="display:none !important" tabindex="-1" autocomplete="off">
                <!-- Where and When -->
                <div class="section-title">
                    <span class="section-number">1</span>
                    Where and When?
                </div>
                <div class="form-group">
                    <label for="address">Property Address</label>
                    <input type="text" name="address" id="address" placeholder="Street Address" required
                        value="{{ old('address') }}">
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <input type="text" name="suite" placeholder="Apt/Suite #" style="margin-top: 10px;"
                        value="{{ old('suite') }}">
                    <div class="address-row">
                        <input type="text" name="city" style="margin-top: 10px;" placeholder="City" required
                            value="{{ old('city') }}">
                        <select name="area" style="margin-top: 10px;" required>
                            <option value="">Select Area</option>
                            <optgroup label="Toronto">
                                <option value="Downtown Toronto"
                                    {{ old('area') == 'Downtown Toronto' ? 'selected' : '' }}>Downtown Toronto</option>
                                <option value="Midtown Toronto"
                                    {{ old('area') == 'Midtown Toronto' ? 'selected' : '' }}>Midtown Toronto</option>
                                <option value="North York" {{ old('area') == 'North York' ? 'selected' : '' }}>North
                                    York</option>
                                <option value="Scarborough" {{ old('area') == 'Scarborough' ? 'selected' : '' }}>
                                    Scarborough</option>
                                <option value="Etobicoke" {{ old('area') == 'Etobicoke' ? 'selected' : '' }}>Etobicoke
                                </option>
                            </optgroup>
                            <optgroup label="GTA Regions">
                                <option value="Mississauga" {{ old('area') == 'Mississauga' ? 'selected' : '' }}>
                                    Mississauga</option>
                                <option value="Brampton" {{ old('area') == 'Brampton' ? 'selected' : '' }}>Brampton
                                </option>
                                <option value="Vaughan" {{ old('area') == 'Vaughan' ? 'selected' : '' }}>Vaughan
                                </option>
                                <option value="Markham" {{ old('area') == 'Markham' ? 'selected' : '' }}>Markham
                                </option>
                                <option value="Richmond Hill" {{ old('area') == 'Richmond Hill' ? 'selected' : '' }}>
                                    Richmond Hill</option>
                                <option value="Newmarket" {{ old('area') == 'Newmarket' ? 'selected' : '' }}>Newmarket
                                </option>
                                <option value="Aurora" {{ old('area') == 'Aurora' ? 'selected' : '' }}>Aurora</option>
                                <option value="Pickering" {{ old('area') == 'Pickering' ? 'selected' : '' }}>Pickering
                                </option>
                                <option value="Whitby" {{ old('area') == 'Whitby' ? 'selected' : '' }}>Whitby</option>
                                <option value="Oshawa" {{ old('area') == 'Oshawa' ? 'selected' : '' }}>Oshawa</option>
                                <option value="Clarington" {{ old('area') == 'Clarington' ? 'selected' : '' }}>
                                    Clarington</option>
                                <option value="Oakville" {{ old('area') == 'Oakville' ? 'selected' : '' }}>Oakville
                                </option>
                                <option value="Burlington" {{ old('area') == 'Burlington' ? 'selected' : '' }}>
                                    Burlington</option>
                            </optgroup>
                        </select>
                        <input type="text" name="postal_code" style="margin-top: 10px;" placeholder="Postal Code"
                            required value="{{ old('postal_code') }}"
                            title="Please enter a valid Canadian postal code (e.g. M5A 1A1)">
                    </div>
                    <div class="postal-code-hint">We serve all GTA areas including Toronto, Mississauga, Brampton,
                        Vaughan, Markham, Richmond Hill, and more.</div>
                    @error('postal_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="service_date">Service Date & Time</label>
                    <p class="note">Our working hours are 9am to 8pm. Cleaning typically takes 2-6 hours depending on
                        property size.</p>
                    <div class="datetime-row">
                        <input type="date" id="service_date" name="service_date" required
                            value="{{ old('service_date') }}">
                        <select id="service_time" name="service_time" required>
                            <option value="">Select Time</option>
                            @foreach (['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'] as $time)
                                <option value="{{ $time }}"
                                    {{ old('service_time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('service_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('service_time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Service Frequency</label>
                    <p class="note">Recurring service discounts apply after first cleaning.</p>
                    <div class="frequency-options">
                        @foreach ($frequencies as $frequency)
                            <label class="frequency-label">
                                <input type="radio" name="frequency_id" value="{{ $frequency->id }}"
                                    {{ old('frequency_id') == $frequency->id ? 'checked' : ($loop->first ? 'checked' : '') }}
                                    data-discount="{{ $frequency->discount_rate ?? 0 }}">
                                <div class="card-content">
                                    <h4>{{ $frequency->name }}</h4>
                                    @if ($frequency->discount_rate > 0)
                                        <p>{{ $frequency->discount_rate }}% off</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('frequency_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Cleaning Details -->
                <div class="section-title">
                    <span class="section-number">2</span>
                    Cleaning Details
                </div>
                <div class="form-group">
                    <label for="cleaning_type_id">Service Type</label>
                    <select name="cleaning_type_id" id="cleaning_type_id" required>
                        <option value="">Select Service Type</option>
                        @foreach ($cleaningTypes as $type)
                            <option value="{{ $type->id }}" data-rate="{{ $type->rate }}"
                                data-package="{{ $type->is_package ? 'true' : 'false' }}"
                                {{ old('cleaning_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cleaning_type_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="dropdown-grid" id="property-details-section">
                    <div class="form-group" id="square-footage-group">
                        <label for="square_footage_id">Home Size</label>
                        <select name="square_footage_id" id="square_footage_id" required>
                            <option value="">Select Sqft</option>
                            @foreach ($squareFootages as $size)
                                <option value="{{ $size->id }}" data-rate="{{ $size->rate }}"
                                    {{ old('square_footage_id') == $size->id ? 'selected' : '' }}>
                                    {{ $size->name }}
                                    @if ($size->rate > 0)
                                        (+${{ number_format($size->rate) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('square_footage_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="bedrooms-group">
                        <label for="bedrooms_id">Bedrooms</label>
                        <select name="bedrooms_id" id="bedrooms_id" required>
                            <option value="">Select Bedrooms</option>
                            @foreach ($bedrooms as $bedroom)
                                <option value="{{ $bedroom->id }}" data-rate="{{ $bedroom->rate }}"
                                    {{ old('bedrooms_id') == $bedroom->id ? 'selected' : '' }}>
                                    {{ $bedroom->name }}
                                    @if ($bedroom->rate > 0)
                                        (+${{ number_format($bedroom->rate, 2) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('bedrooms_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="bathrooms-group">
                        <label for="bathrooms_id">Bathrooms</label>
                        <select name="bathrooms_id" id="bathrooms_id" required>
                            <option value="">Select Bathrooms</option>
                            @foreach ($bathrooms as $bathroom)
                                <option value="{{ $bathroom->id }}" data-rate="{{ $bathroom->rate }}"
                                    {{ old('bathrooms_id') == $bathroom->id ? 'selected' : '' }}>
                                    {{ $bathroom->name }}
                                    @if ($bathroom->rate > 0)
                                        (+${{ number_format($bathroom->rate, 2) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('bathrooms_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- Customize Cleaning -->
                <div class="section-title">
                    <span class="section-number">3</span>
                    Customize Your Service
                </div>
                <div class="addon-grid">
                    @foreach ($customOptions as $index => $option)
                        <label class="cleaning-option-card">
                            <input type="checkbox" name="custom_options[]" value="{{ $option['id'] }}"
                                data-price="{{ $option['rate'] }}" class="customize-option-checkbox"
                                @if (isset($selectedOptions) && in_array($option['id'], $selectedOptions)) checked @endif>
                            <div class="option-content">
                                <!-- Fixed-size image container -->
                                <div class="option-image-container">
                                    @if (isset($option['image_path']))
                                        <img src="{{ asset('storage/' . $option['image_path']) }}"
                                            alt="{{ $option['name'] }}">
                                    @else
                                        <div class="image-placeholder"></div>
                                    @endif
                                </div>
                                <h4>{{ $option['name'] }}</h4>
                                <p>{{ $option['description'] }}</p>
                                <span class="option-price">+${{ number_format($option['rate'], 2) }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="cleaning_instructions">Special Requests</label>
                    <textarea id="cleaning_instructions" name="cleaning_instructions" rows="3"
                        placeholder="Any specific instructions or areas of focus (e.g., specific rooms only at $53/hour per cleaner)...">{{ old('cleaning_instructions') }}</textarea>
                </div>
                <!-- Contact Info -->
                <div class="section-title">
                    <span class="section-number">4</span>
                    Your Information
                </div>
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="first_name" id="first_name" required
                        value="{{ old('first_name') }}">
                    @error('first_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" id="last_name" required value="{{ old('last_name') }}">
                    @error('last_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone *</label>
                    <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}"
                        title="Please enter a 10-digit phone number">
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Final Details -->
                <div class="section-title">
                    <span class="section-number">5</span>
                    Final Details
                </div>
                <div class="form-group">
                    <label>Access Instructions *</label>
                    <textarea name="access_info" rows="3" required placeholder="How should our team access your property?">{{ old('access_info') }}</textarea>
                    @error('access_info')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Parking Information</label>
                    <input type="text" name="parking" placeholder="Parking instructions or restrictions"
                        value="{{ old('parking') }}">
                </div>
                <div class="form-group">
                    <label>Property Type</label>
                    <div class="property-types">
                        <label class="property-type">
                            <input type="radio" name="property-type" value="house" checked>
                            <div class="type-wrapper">
                                <div class="icon">🏠</div>
                                <div class="label">House</div>
                            </div>
                        </label>
                        <label class="property-type">
                            <input type="radio" name="property-type" value="apartment">
                            <div class="type-wrapper">
                                <div class="icon">🏢</div>
                                <div class="label">Apartment</div>
                            </div>
                        </label>
                        <label class="property-type">
                            <input type="radio" name="property-type" value="condo">
                            <div class="type-wrapper">
                                <div class="icon">🏘️</div>
                                <div class="label">Condo</div>
                            </div>
                        </label>
                        <label class="property-type">
                            <input type="radio" name="property-type" value="office">
                            <div class="type-wrapper">
                                <div class="icon">🏬</div>
                                <div class="label">Office</div>
                            </div>
                        </label>
                    </div>
                    @error('property_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="coupon_code">Coupon Code</label>
                    <div class="input-group">
                        <input type="text" id="coupon_code" name="coupon_code" placeholder="Enter coupon code">
                        <button type="button" id="apply-coupon-btn" class="btn btn-primary">Apply</button>
                    </div>
                    <div id="coupon-message" class="coupon-message"></div>
                    <input type="hidden" id="coupon_discount" name="coupon_discount" value="0">
                    <input type="hidden" id="applied_coupon_code" name="applied_coupon_code" value="">
                    <input type="hidden" name="subtotal" id="subtotal_field" value="">
                </div>
                <!-- Payment -->
                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" name="payment_method" id="credit_card" value="credit_card">
                            <label class="payment-method-wrapper" for="credit_card">
                                <span class="icon">💳</span>
                                <span class="label">Credit Card</span>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" name="payment_method" id="debit" value="debit">
                            <label class="payment-method-wrapper" for="debit">
                                <span class="icon">💳</span>
                                <span class="label">Debit Card</span>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" name="payment_method" id="cash" value="cash" checked>
                            <label class="payment-method-wrapper" for="cash">
                                <span class="icon">💵</span>
                                <span class="label">Cash</span>
                            </label>
                        </div>
                    </div>
                    <p class="important-note">Payment is not required until the day of service.</p>
                    @error('payment_method')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="terms_accepted" value="1" required>
                        I agree to the <a href="/terms" target="_blank" style="color: #22c55e;">Terms</a> &
                        <a href="/privacy" target="_blank" style="color: #22c55e;">Privacy Policy</a>.
                    </label>
                    @error('terms_accepted')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <input type="hidden" name="subtotal" id="subtotal_field" value="">
                <button type="submit" id="submit-btn" class="btn btn-success">
                    Book My Cleaning Service
                </button>
                <input type="hidden" id="old-input" value="{{ json_encode(old() ?: '{}') }}">
            </form>
        </div>
        <!-- Summary Section -->
        <div class="summary-section">
            <h2 class="summary-title">Booking Summary</h2>
            <div class="summary-item">
                <span class="summary-label">Service Date:</span>
                <span class="summary-value" id="summary-date">Not selected</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Service Time:</span>
                <span class="summary-value" id="summary-time">Not selected</span>
            </div>
            <div class="summary-section-divider"></div>
            <div class="summary-item">
                <span class="summary-label">Service Type:</span>
                <span class="summary-value" id="summary-service-type">Not selected</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Home Size:</span>
                <span class="summary-value" id="summary-home-size">Not selected</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Bedrooms:</span>
                <span class="summary-value" id="summary-bedrooms">Not selected</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Bathrooms:</span>
                <span class="summary-value" id="summary-bathrooms">Not selected</span>
            </div>
            <div class="summary-section-divider"></div>
            <div class="summary-item">
                <span class="summary-label">Frequency:</span>
                <span class="summary-value" id="summary-frequency">One-time</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Additional Services:</span>
                <span class="summary-value" id="summary-addons">None</span>
            </div>
            <div class="summary-section-divider"></div>
            <div class="total-section">
                <div class="total-amount" id="total-amount">$0.00</div>
                <div style="text-align: center; margin-top: 10px; font-size: 0.9rem; color: rgba(255, 255, 255, 0.7);">
                    Estimated Total
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('booking-form');

            if (bookingForm) {
                bookingForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Clear previous errors
                    clearFormErrors();

                    // Disable submit button
                    const submitBtn = document.getElementById('submit-btn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
                    }

                    try {
                        const formData = new FormData(bookingForm);
                        const response = await fetch(bookingForm.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            if (data.errors) {
                                displayFormErrors(data.errors);
                            } else if (data.message) {
                                displayGeneralError(data.message);
                            }
                            return;
                        }

                        // Success - redirect
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        displayGeneralError('An error occurred. Please try again.');
                    } finally {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'Book My Cleaning Service';
                        }
                    }
                });
            }

            function clearFormErrors() {
                // Clear general errors
                const errorContainer = document.getElementById('form-errors');
                if (errorContainer) {
                    errorContainer.innerHTML = '';
                    errorContainer.style.display = 'none';
                }

                // Clear field-specific errors
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.remove();
                });
            }

            function displayFormErrors(errors) {
                const errorContainer = document.getElementById('form-errors');
                if (!errorContainer) return;

                errorContainer.innerHTML = '';
                errorContainer.style.display = 'block';

                const errorList = document.createElement('ul');

                Object.entries(errors).forEach(([field, messages]) => {
                    messages.forEach(message => {
                        const item = document.createElement('li');
                        item.textContent = message;
                        errorList.appendChild(item);

                        // Highlight the problematic field
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');

                            // Add error message near the field
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = message;
                            input.closest('.form-group')?.append(feedback);
                        }
                    });
                });

                errorContainer.appendChild(errorList);
                errorContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            function displayGeneralError(message) {
                const errorContainer = document.getElementById('form-errors');
                if (!errorContainer) return;

                errorContainer.innerHTML = `
                    <div class="alert alert-danger">
                        ${message}
                    </div>
                `;
                errorContainer.style.display = 'block';
                errorContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
</body>

</html>
