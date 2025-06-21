function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('open');
}

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
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: isFormData ? data : new URLSearchParams(data)
        };

        if (!isFormData) {
            options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        try {
            // Add loading state to button
            button.disabled = true;
            button.classList.add('btn-saving');

            const response = await fetch(url, options);
            const responseData = await response.json();

            if (!response.ok) {
                throw responseData;
            }

            showToast(successMessage);

            // Update image preview if new image was uploaded
            if (responseData.image_url) {
                const imgPreview = button.closest('tr').querySelector('.addon-image-preview');
                if (imgPreview) {
                    imgPreview.src = responseData.image_url;
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
            // Remove loading state
            button.disabled = false;
            button.classList.remove('btn-saving');
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

    setupSaveHandler(
        '.save-service-type',
        (row) => {
            const rate = row.querySelector('.service-rate').value;
            const isPackage = row.querySelector('.is-package').checked ? 1 : 0;

            if (!rate || rate < 0) {
                return {
                    error: 'Please enter a valid rate'
                };
            }

            return {
                payload: {
                    rate,
                    is_package: isPackage
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
        'Square footage rate updated successfully'
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
        'Bedroom rate updated successfully'
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
        'Bathroom rate updated successfully'
    );

    // Special handler for addons with image upload
    document.querySelectorAll('.save-addon').forEach(button => {
        button.addEventListener('click', async function() {
            const row = this.closest('tr');
            const rate = row.querySelector('.addon-rate').value;
            const imageInput = row.querySelector('.addon-image');

            if (!rate || rate < 0) {
                showToast('Please enter a valid rate', false);
                return;
            }

            const formData = new FormData();
            formData.append('rate', rate);

            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }

            await saveData(
                `/custom-options/${this.dataset.id}`,
                formData,
                'Addon updated successfully',
                this
            );

            // Clear file input after successful upload
            if (imageInput.files[0]) {
                imageInput.value = '';
            }
        });
    });

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

    // Event delegation for delete buttons
    document.getElementById('frequencies-tbody').addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-frequency')) {
            const frequencyId = e.target.getAttribute('data-id');
            deleteFrequency(frequencyId);
        }
    });

    async function deleteFrequency(id) {
        if (!confirm('Are you sure you want to delete this item?')) return;

        try {
            console.log(`Attempting to delete frequency ID: ${id}`); // Debug log

            const response = await fetch(`/frequencies/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            console.log('Response status:', response.status); // Debug log

            const data = await response.json();
            console.log('Server response:', data); // Debug log

            if (response.ok) {
                document.getElementById(`row-${id}`).remove();
                alert('Deleted successfully!');
            } else {
                throw new Error(data.message || 'Failed to delete');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            // Re-add the row if deletion failed
            location.reload(); // Simple solution - reload the page
        }
    }

    console.log('Pricing management page loaded');
});
