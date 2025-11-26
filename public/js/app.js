// API Configuration
window.API_BASE_URL = window.API_BASE_URL || (document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') || '/api');
window.API_TOKEN = localStorage.getItem('api_token') || null;

// Axios configuration
if (typeof axios !== 'undefined') {
    // Configure axios to send credentials (cookies) with requests
    axios.defaults.withCredentials = true;
    
    if (window.API_TOKEN) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.API_TOKEN;
    }
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);

    // Login form handler for modal - simple approach
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const errorDiv = document.getElementById('loginError');
            if (errorDiv) errorDiv.classList.add('d-none');
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const remember = document.getElementById('rememberMe')?.checked || false;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                formData.append('_token', csrfToken);
                if (remember) {
                    formData.append('remember', '1');
                }
                
                const response = await fetch('/login', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok && response.status !== 422) {
                    throw new Error('Error en la petición');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Close modal and redirect
                    const modalElement = document.getElementById('loginModal');
                    if (modalElement && typeof bootstrap !== 'undefined') {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();
                    }
                    window.location.href = data.redirect || '/dashboard';
                } else {
                    // Show error
                    if (errorDiv) {
                        errorDiv.textContent = data.message || 'Error al iniciar sesión';
                        errorDiv.classList.remove('d-none');
                    }
                }
            } catch (error) {
                console.error('Login error:', error);
                if (errorDiv) {
                    errorDiv.textContent = 'Error al iniciar sesión. Por favor, intente nuevamente.';
                    errorDiv.classList.remove('d-none');
                }
            }
        });
        
        // Reset form when modal is closed
        const loginModal = document.getElementById('loginModal');
        if (loginModal && typeof bootstrap !== 'undefined') {
            loginModal.addEventListener('hidden.bs.modal', function() {
                loginForm.reset();
                const errorDiv = document.getElementById('loginError');
                if (errorDiv) errorDiv.classList.add('d-none');
            });
        }
    }
    
    // Initialize tooltips for login buttons
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('.login-btn-link'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        }
    });

    // Initialize language dropdown (Bootstrap 4)
    $('.dropdown-toggle').dropdown();

    // Language switcher functionality
    $('.lang-switcher').on('click', function(e) {
        e.preventDefault();
        const locale = $(this).data('locale');

        // Show loading indicator if needed
        const $link = $(this);
        const originalText = $link.text();

        $.ajax({
            url: '/lang/' + locale,
            method: 'GET',
            success: function() {
                location.reload();
            },
            error: function() {
                alert('Error cambiando idioma. Intente nuevamente.');
            }
        });
    });
});

