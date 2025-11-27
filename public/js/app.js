/**
 * App.js - Main application JavaScript
 * Handles API configuration, authentication, and UI interactions
 */

// API Configuration - Base URL and token management
window.API_BASE_URL = window.API_BASE_URL || (document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') || '/api');
window.API_TOKEN = localStorage.getItem('api_token') || null;

// Axios HTTP client configuration for API requests
if (typeof axios !== 'undefined') {
    // Configure axios to send credentials (cookies) with requests for CSRF protection
    axios.defaults.withCredentials = true;

    // Set authorization header if token exists
    if (window.API_TOKEN) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.API_TOKEN;
    }

    // Set CSRF token for Laravel requests
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
    
    // Language switcher functionality (Bootstrap 5 native)
    document.querySelectorAll('.lang-switcher').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const locale = this.getAttribute('data-locale');
            
            // Navigate to language switch endpoint
            fetch('/lang/' + locale, {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(function() {
                location.reload();
            })
            .catch(function() {
                alert('Error cambiando idioma. Intente nuevamente.');
            });
        });
    });
});

