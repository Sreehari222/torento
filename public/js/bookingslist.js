document.addEventListener('DOMContentLoaded', function() {
    // Make rows clickable
    document.querySelectorAll('.bookings-table tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('a, button, input, select, textarea')) {
                const viewBtn = this.querySelector('.bookings-action-view');
                if (viewBtn) window.location = viewBtn.href;
            }
        });

        if (!row.querySelector('td:last-child').querySelector('a, button')) {
            row.style.cursor = 'pointer';
        }
    });

    // Delete confirmation
    document.querySelectorAll('.bookings-action-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this booking?')) {
                e.preventDefault();
            }
        });
    });
});
