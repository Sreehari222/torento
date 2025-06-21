document.addEventListener('DOMContentLoaded', function () {
    // ======================
    // 1. ELEMENT SELECTORS
    // ======================
    const bookingForm = document.getElementById('booking-form');
    if (!bookingForm) {
        console.error('Booking form not found!');
        return;
    }

    // Form controls
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    const couponMessage = document.getElementById('coupon-message');
    const couponCodeInput = document.getElementById('coupon_code');
    const couponDiscountInput = document.getElementById('coupon_discount');
    const couponDiscountTypeInput = document.getElementById('coupon_discount_type');
    const subtotalField = document.getElementById('subtotal_field');
    const couponDiscountDisplay = document.getElementById('coupon-discount-display');
    const appliedCouponField = document.getElementById('applied_coupon_code');

    // Create hidden field for original subtotal if it doesn't exist
    let originalSubtotalField = document.getElementById('original_subtotal');
    if (!originalSubtotalField) {
        originalSubtotalField = document.createElement('input');
        originalSubtotalField.type = 'hidden';
        originalSubtotalField.name = 'original_subtotal';
        originalSubtotalField.id = 'original_subtotal';
        bookingForm.appendChild(originalSubtotalField);
    }

    // Service selection
    const cleaningTypeSelect = document.getElementById('cleaning_type_id');
    const squareFootageSelect = document.getElementById('square_footage_id');
    const bedroomsSelect = document.getElementById('bedrooms_id');
    const bathroomsSelect = document.getElementById('bathrooms_id');
    const frequencyRadios = document.querySelectorAll('input[name="frequency_id"]');
    const customizeOptions = document.querySelectorAll('.customize-option-checkbox');
    const propertyDetailsSection = document.getElementById('property-details-section');

    // Date/time
    const serviceDateInput = document.getElementById('service_date');
    const serviceTimeSelect = document.getElementById('service_time');

    // Customer info
    const phoneInput = document.getElementById('phone');

    // Summary elements
    const summaryDate = document.getElementById('summary-date');
    const summaryTime = document.getElementById('summary-time');
    const summaryServiceType = document.getElementById('summary-service-type');
    const summaryHomeSize = document.getElementById('summary-home-size');
    const summaryBedrooms = document.getElementById('summary-bedrooms');
    const summaryBathrooms = document.getElementById('summary-bathrooms');
    const summaryFrequency = document.getElementById('summary-frequency');
    const summaryAddons = document.getElementById('summary-addons');
    const totalAmount = document.getElementById('total-amount');
    const summaryCouponDiscount = document.getElementById('summary-coupon-discount');

    // ======================
    // 2. INITIAL SETUP
    // ======================

    // Set min date for service date
    const today = new Date();
    const dd = String(today.getDate()).padStart(2, '0');
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const yyyy = today.getFullYear();
    const minDate = `${yyyy}-${mm}-${dd}`;
    if (serviceDateInput) serviceDateInput.setAttribute('min', minDate);

    // Phone number formatting
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 10) value = value.slice(0, 10);
            if (value.length >= 7) {
                this.value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
            } else if (value.length >= 4) {
                this.value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
            } else if (value.length > 0) {
                this.value = `(${value}`;
            } else {
                this.value = '';
            }
        });
    }

    // ======================
    // 3. FORM HANDLING
    // ======================

    /**
     * Handle form submission
     */
    async function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const errorContainer = document.getElementById('form-errors');

        try {
            // Clear previous errors
            if (errorContainer) {
                errorContainer.innerHTML = '';
                errorContainer.style.display = 'none';
            }

            // Disable button during submission
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

            // Convert FormData to JSON
            const formData = new FormData(form);
            const jsonData = {};

            // Handle checkboxes, arrays, and empty values
            formData.forEach((value, key) => {
                // Remove empty fields
                if (value === '') return;

                // Handle array fields (like custom_options[])
                if (key.endsWith('[]')) {
                    const realKey = key.replace('[]', '');
                    jsonData[realKey] = formData.getAll(key).filter(v => v !== '');
                }
                // Handle regular fields
                else {
                    jsonData[key] = value;
                }
            });

            // Explicitly handle checkboxes
            jsonData.terms_accepted = form.querySelector('[name="terms_accepted"]').checked;

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(jsonData)
            });

            const data = await response.json();

            if (!response.ok) {
                // Handle validation errors
                if (data.errors) {
                    displayFormErrors(data.errors);
                } else {
                    throw new Error(data.message || 'Server error occurred');
                }
                return;
            }

            // Success case - redirect
            if (data.redirect) {
                window.location.href = data.redirect;
            }

        } catch (error) {
            console.error('Submission error:', error);
            if (errorContainer) {
                errorContainer.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
                errorContainer.style.display = 'block';
            }
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Submit Booking';
        }
    }

    function displayFormErrors(errors) {
        const errorContainer = document.getElementById('form-errors');
        if (!errorContainer) return;

        errorContainer.innerHTML = '';
        errorContainer.style.display = 'block';

        const errorList = document.createElement('ul');
        errorList.className = 'list-unstyled mb-0';

        // Process all error messages
        Object.entries(errors).forEach(([field, messages]) => {
            messages.forEach(message => {
                const item = document.createElement('li');
                item.textContent = message;
                errorList.appendChild(item);

                // Highlight the problematic field
                const input = document.querySelector(`[name="${field}"], [name="${field}[]"]`);
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
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Initialize form submission handler
    bookingForm.addEventListener('submit', handleFormSubmit);

    /**
     * Validate form inputs
     */
    function validateForm() {
        let isValid = true;

        // Clear previous errors
        const errorElements = bookingForm.querySelectorAll('.error-message');
        errorElements.forEach(el => el.textContent = '');

        // Check postal code
        const postalCode = bookingForm.querySelector('input[name="postal_code"]')?.value;
        const postalCodeRegex = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/i;
        if (!postalCode || !postalCodeRegex.test(postalCode)) {
            showFieldError('postal_code', 'Please enter a valid Canadian postal code (e.g., M5A 1A1)');
            isValid = false;
        }

        // Check phone number
        const phone = bookingForm.querySelector('input[name="phone"]')?.value.replace(/\D/g, '');
        if (!phone || phone.length !== 10) {
            showFieldError('phone', 'Please enter a valid 10-digit phone number');
            isValid = false;
        }

        // Add similar validation for other required fields
        const requiredFields = [
            'address', 'city', 'area', 'service_date', 'service_time',
            'frequency_id', 'cleaning_type_id', 'first_name', 'last_name',
            'email', 'access_info', 'property_type'
        ];

        requiredFields.forEach(field => {
            const element = bookingForm.querySelector(`[name="${field}"]`);
            if (element && !element.value && !element.checked) {
                showFieldError(field, 'This field is required');
                isValid = false;
            }
        });

        // Check property details if not a package
        if (cleaningTypeSelect && cleaningTypeSelect.selectedOptions[0]?.dataset.package !== 'true') {
            const propertyFields = ['square_footage_id', 'bedrooms_id', 'bathrooms_id'];
            propertyFields.forEach(field => {
                const element = bookingForm.querySelector(`[name="${field}"]`);
                if (element && !element.value) {
                    showFieldError(field, 'This field is required');
                    isValid = false;
                }
            });
        }

        return isValid;
    }

    function showFieldError(fieldName, message) {
        const field = bookingForm.querySelector(`[name="${fieldName}"]`);
        if (!field) return;

        let errorElement = field.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('error-message')) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message text-danger';
            field.parentNode.insertBefore(errorElement, field.nextSibling);
        }

        errorElement.textContent = message;
    }

    // ======================
    // 4. PRICE CALCULATION
    // ======================

    /**
     * Calculate the base price without any discounts
     */
    function calculateBasePrice() {
        const cleaningTypeOption = cleaningTypeSelect.options[cleaningTypeSelect.selectedIndex];
        const baseRate = parseFloat(cleaningTypeOption?.dataset.rate) || 0;
        const isPackage = cleaningTypeOption?.dataset.package === 'true';

        // Calculate property rates
        const squareFootageRate = isPackage ? 0 :
            (squareFootageSelect?.selectedOptions[0] ? parseFloat(squareFootageSelect.selectedOptions[0].dataset.rate) || 0 : 0);
        const bedroomsRate = isPackage ? 0 :
            (bedroomsSelect?.selectedOptions[0] ? parseFloat(bedroomsSelect.selectedOptions[0].dataset.rate) || 0 : 0);
        const bathroomsRate = isPackage ? 0 :
            (bathroomsSelect?.selectedOptions[0] ? parseFloat(bathroomsSelect.selectedOptions[0].dataset.rate) || 0 : 0);

        // Calculate addons
        let addonsTotal = 0;
        document.querySelectorAll('.customize-option-checkbox:checked').forEach(checkbox => {
            const price = parseFloat(checkbox.dataset.price);
            if (!isNaN(price)) {
                addonsTotal += price;
            }
        });

        return baseRate + squareFootageRate + bedroomsRate + bathroomsRate + addonsTotal;
    }

    /**
     * Update booking summary and price
     */
    function updateSummaryAndPrice() {
        updateSummary();
        calculateTotal();
    }

    /**
     * Update booking summary display
     */
    function updateSummary() {
        if (serviceDateInput && summaryDate) {
            summaryDate.textContent = serviceDateInput.value ?
                new Date(serviceDateInput.value).toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) : 'Not selected';
        }

        if (serviceTimeSelect && summaryTime) {
            summaryTime.textContent = serviceTimeSelect.value ?
                serviceTimeSelect.options[serviceTimeSelect.selectedIndex].text : 'Not selected';
        }

        if (cleaningTypeSelect && summaryServiceType) {
            summaryServiceType.textContent = cleaningTypeSelect.value ?
                cleaningTypeSelect.options[cleaningTypeSelect.selectedIndex].text : 'Not selected';
        }

        if (squareFootageSelect && summaryHomeSize) {
            summaryHomeSize.textContent = squareFootageSelect.value ?
                squareFootageSelect.options[squareFootageSelect.selectedIndex].text : 'Not selected';
        }

        if (bedroomsSelect && summaryBedrooms) {
            summaryBedrooms.textContent = bedroomsSelect.value ?
                bedroomsSelect.options[bedroomsSelect.selectedIndex].text : 'Not selected';
        }

        if (bathroomsSelect && summaryBathrooms) {
            summaryBathrooms.textContent = bathroomsSelect.value ?
                bathroomsSelect.options[bathroomsSelect.selectedIndex].text : 'Not selected';
        }

        if (summaryFrequency) {
            const selectedFrequency = document.querySelector('input[name="frequency_id"]:checked');
            summaryFrequency.textContent = selectedFrequency ?
                selectedFrequency.closest('.frequency-label')?.querySelector('h4')?.textContent || 'One-time' : 'One-time';
        }

        if (summaryAddons) {
            const selectedAddons = Array.from(document.querySelectorAll('.customize-option-checkbox:checked'))
                .map(checkbox => checkbox.closest('.cleaning-option-card')?.querySelector('h4')?.textContent)
                .filter(Boolean);
            summaryAddons.textContent = selectedAddons.length > 0 ? selectedAddons.join(', ') : 'None';
        }

        // Update coupon discount display
        if (summaryCouponDiscount) {
            const couponDiscount = couponDiscountInput ? parseFloat(couponDiscountInput.value) || 0 : 0;
            const discountType = couponDiscountTypeInput ? couponDiscountTypeInput.value : 'percentage';

            if (couponDiscount > 0) {
                const discountText = discountType === 'percentage'
                    ? `${couponDiscount}% off`
                    : `$${couponDiscount.toFixed(2)} off`;
                summaryCouponDiscount.textContent = discountText;
                summaryCouponDiscount.style.display = 'block';
            } else {
                summaryCouponDiscount.textContent = '';
                summaryCouponDiscount.style.display = 'none';
            }
        }
    }

    /**
     * Calculate total price (updated to prevent double discounting)
     */
    function calculateTotal() {
        if (!cleaningTypeSelect || !subtotalField || !totalAmount) return;

        // Calculate base price without any discounts
        const basePrice = calculateBasePrice();
        originalSubtotalField.value = basePrice.toFixed(2);

        // Apply frequency discount
        const selectedFrequency = document.querySelector('input[name="frequency_id"]:checked');
        const frequencyDiscount = selectedFrequency ? parseFloat(selectedFrequency.dataset.discount) || 0 : 0;
        const frequencyDiscountAmount = frequencyDiscount > 0 ? basePrice * (frequencyDiscount / 100) : 0;

        // Calculate subtotal after frequency discount (this is what we send to server)
        let subtotal = basePrice - frequencyDiscountAmount;
        subtotal = Math.max(0, subtotal);
        subtotalField.value = subtotal.toFixed(2);

        // Apply coupon discount for display only
        const couponDiscount = couponDiscountInput ? parseFloat(couponDiscountInput.value) || 0 : 0;
        let displayTotal = subtotal - couponDiscount;
        displayTotal = Math.max(0, displayTotal);

        // Update display
        totalAmount.textContent = `$${displayTotal.toFixed(2)}`;

        // Update coupon discount display if exists
        if (couponDiscountDisplay) {
            if (couponDiscount > 0) {
                couponDiscountDisplay.textContent = `-$${couponDiscount.toFixed(2)}`;
                couponDiscountDisplay.style.display = 'block';
            } else {
                couponDiscountDisplay.textContent = '';
                couponDiscountDisplay.style.display = 'none';
            }
        }
    }

    // ======================
    // 5. COUPON VALIDATION
    // ======================

    /**
     * Apply coupon code
     */
    async function applyCoupon(e) {
        e.preventDefault();

        // Validate required elements
        if (!couponCodeInput || !couponMessage || !applyCouponBtn) {
            console.error('Missing coupon elements');
            return;
        }

        const couponCode = couponCodeInput.value.trim();
        if (!couponCode) {
            showCouponMessage('Please enter a coupon code', 'error');
            return;
        }

        // Reset previous states
        resetCouponUIState();

        // Set loading state
        setLoadingState(true);

        try {
            // Get the original subtotal (before any discounts)
            const originalSubtotal = parseFloat(originalSubtotalField.value) || calculateBasePrice();

            const response = await fetch('/validate-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    coupon_code: couponCode,
                    subtotal: originalSubtotal
                })
            });

            const data = await handleResponse(response);

            if (data.valid) {
                handleSuccessfulCoupon(couponCode, data, originalSubtotal);
            } else {
                handleInvalidCoupon(data.message);
            }
        } catch (error) {
            handleCouponError(error);
        } finally {
            setLoadingState(false);
            updateSummaryAndPrice();
        }
    }

    // Helper Functions for Coupon Validation
    function resetCouponUIState() {
        couponMessage.textContent = '';
        couponMessage.className = 'coupon-message';
        couponMessage.style.display = 'none';
        couponCodeInput.classList.remove('is-invalid', 'is-valid');
    }

    function setLoadingState(isLoading) {
        if (isLoading) {
            applyCouponBtn.disabled = true;
            applyCouponBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Applying...';
        } else {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = 'Apply Coupon';
        }
    }

    async function handleResponse(response) {
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Coupon validation failed');
        }

        return data;
    }

    function handleSuccessfulCoupon(couponCode, data, originalSubtotal) {
        // Safely parse discount values
        const discountValue = parseFloat(data.discount_amount) || 0;
        const discountType = data.discount_type || 'percentage';
        const maxDiscount = data.max_discount_amount ? parseFloat(data.max_discount_amount) : null;

        // Calculate actual discount amount
        let calculatedDiscount = 0;

        if (discountType === 'percentage') {
            calculatedDiscount = originalSubtotal * (discountValue / 100);
            if (maxDiscount !== null) {
                calculatedDiscount = Math.min(calculatedDiscount, maxDiscount);
            }
        } else {
            calculatedDiscount = Math.min(discountValue, originalSubtotal);
        }

        // Store coupon details
        couponDiscountInput.value = calculatedDiscount.toFixed(2);
        if (couponDiscountTypeInput) {
            couponDiscountTypeInput.value = discountType;
        }
        if (appliedCouponField) {
            appliedCouponField.value = couponCode;
        }

        // Update UI
        couponCodeInput.classList.add('is-valid');
        const discountText = discountType === 'percentage'
            ? `${discountValue}% discount ($${calculatedDiscount.toFixed(2)})`
            : `$${calculatedDiscount.toFixed(2)} discount`;

        showCouponMessage(`Coupon applied! ${discountText}`, 'success');
    }

    function handleInvalidCoupon(message) {
        resetCouponValues();
        showCouponMessage(message || 'Invalid coupon code', 'error');
        couponCodeInput.classList.add('is-invalid');
    }

    function handleCouponError(error) {
        console.error('Coupon error:', error);
        resetCouponValues();
        showCouponMessage(error.message || 'Error validating coupon', 'error');
        couponCodeInput.classList.add('is-invalid');
    }

    function resetCouponValues() {
        if (couponDiscountInput) couponDiscountInput.value = 0;
        if (couponDiscountTypeInput) couponDiscountTypeInput.value = '';
        if (appliedCouponField) appliedCouponField.value = '';

        if (couponDiscountDisplay) {
            couponDiscountDisplay.textContent = '';
            couponDiscountDisplay.style.display = 'none';
        }
    }

    function showCouponMessage(message, type) {
        couponMessage.textContent = message;
        couponMessage.className = `coupon-message alert alert-${type === 'error' ? 'danger' : 'success'}`;
        couponMessage.style.display = 'block';
    }

    // ======================
    // 6. EVENT LISTENERS
    // ======================

    // Add event listeners only if elements exist
    if (serviceDateInput) serviceDateInput.addEventListener('change', updateSummaryAndPrice);
    if (serviceTimeSelect) serviceTimeSelect.addEventListener('change', updateSummaryAndPrice);
    if (cleaningTypeSelect) cleaningTypeSelect.addEventListener('change', handleCleaningTypeChange);
    if (squareFootageSelect) squareFootageSelect.addEventListener('change', updateSummaryAndPrice);
    if (bedroomsSelect) bedroomsSelect.addEventListener('change', updateSummaryAndPrice);
    if (bathroomsSelect) bathroomsSelect.addEventListener('change', updateSummaryAndPrice);

    if (frequencyRadios.length) {
        frequencyRadios.forEach(radio => radio.addEventListener('change', updateSummaryAndPrice));
    }

    if (customizeOptions.length) {
        customizeOptions.forEach(option => option.addEventListener('change', updateSummaryAndPrice));
    }

    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', applyCoupon);
    }

    // ======================
    // 7. HELPER FUNCTIONS
    // ======================

    /**
     * Handle cleaning type change (show/hide property details)
     */
    function handleCleaningTypeChange() {
        if (!cleaningTypeSelect || !propertyDetailsSection) return;

        const selectedOption = cleaningTypeSelect.options[cleaningTypeSelect.selectedIndex];
        const isPackage = selectedOption?.dataset.package === 'true';

        if (isPackage) {
            propertyDetailsSection.classList.add('hidden-by-package');
            if (squareFootageSelect) squareFootageSelect.value = '';
            if (bedroomsSelect) bedroomsSelect.value = '';
            if (bathroomsSelect) bathroomsSelect.value = '';
        } else {
            propertyDetailsSection.classList.remove('hidden-by-package');
        }

        // Reset coupon when service type changes
        resetCouponValues();
        if (couponCodeInput) couponCodeInput.value = '';
        if (couponMessage) {
            couponMessage.textContent = '';
            couponMessage.style.display = 'none';
        }

        updateSummaryAndPrice();
    }

    // Initialize
    updateSummaryAndPrice();
});
