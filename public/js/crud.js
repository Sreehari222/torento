/**
 * Initialize form validation
 * @param {string} formSelector - CSS selector for the form
 */
function initializeFormValidation(formSelector) {
    $(formSelector).validate({
        rules: {
            // Dynamic rules can be added via data attributes
        },
        messages: {
            // Dynamic messages can be added via data attributes
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function(form) {
            // Show loading state
            const submitButton = $(form).find('button[type="submit"]');
            submitButton.prop('disabled', true);
            submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            form.submit();
        }
    });
}

/**
 * Initialize delete confirmation
 * @param {string} formSelector - CSS selector for the delete form
 */
function initializeDeleteConfirmation(formSelector) {
    $(formSelector).on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                const deleteButton = $(formSelector).find('button[type="submit"]');
                deleteButton.prop('disabled', true);
                deleteButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');

                // Submit the form
                this.submit();
            }
        });
    });
}

/**
 * Show toast notification
 * @param {string} type - success, error, warning, info
 * @param {string} message - Message to display
 */
function showToast(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

// Display any flash messages
$(document).ready(function() {
    if ($('#flash-message').length) {
        const type = $('#flash-message').data('type');
        const message = $('#flash-message').text();
        showToast(type, message);
    }
});
