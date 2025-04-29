document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for dropdowns
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Handle filter form submission
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(filterForm);
            const queryString = new URLSearchParams(formData).toString();
            window.location.href = `${window.location.pathname}?${queryString}`;
        });
    }

    // Handle new job offer button click
    document.getElementById('newJobOfferBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        loadForm('/offreemploi/new');
    });

    // Handle edit buttons
    document.querySelectorAll('.edit-job-offer').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            loadForm(`/offreemploi/${id}/edit`);
        });
    });

    // Handle delete buttons
    document.querySelectorAll('.delete-job-offer').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this job offer?')) {
                const id = this.dataset.id;
                fetch(`/offreemploi/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('tr').remove();
                            alert('Job offer deleted successfully');
                        } else {
                            alert(data.message || 'Error deleting job offer');
                        }
                    });
            }
        });
    });

    // Handle toggle status button clicks
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const jobOfferId = this.dataset.id;
            toggleJobOfferStatus(jobOfferId);
        });
    });
});

function loadForm(url) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('jobOfferModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'jobOfferModal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Job Offer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">Loading...</div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    // Load form
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.text())
        .then(html => {
            modal.querySelector('.modal-body').innerHTML = html;

            // Handle form submission
            const form = modal.querySelector('form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            bsModal.hide();
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error saving job offer');
                        }
                    });
            });
        })
        .catch(error => {
            modal.querySelector('.modal-body').innerHTML = 'Error loading form';
        });
}

// Function to toggle job offer status
function toggleJobOfferStatus(jobOfferId) {
    fetch(`/offreemploi/${jobOfferId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update status button
                const button = document.querySelector(`button.toggle-status[data-id="${jobOfferId}"]`);
                if (button) {
                    button.classList.toggle('btn-success');
                    button.classList.toggle('btn-danger');
                    button.textContent = data.newStatus ? 'Active' : 'Inactive';
                }

                showAlert('success', data.message || 'Status updated successfully');
            } else {
                showAlert('danger', data.message || 'Error updating status');
            }
        })
        .catch(error => {
            console.error('Error toggling status:', error);
            showAlert('danger', 'Error updating status. Please try again.');
        });
}

// Function to show alert message
function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) {
        // Create alert container if it doesn't exist
        const container = document.createElement('div');
        container.id = 'alertContainer';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }

    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.role = 'alert';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.getElementById('alertContainer').appendChild(alert);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 150);
    }, 5000);
}