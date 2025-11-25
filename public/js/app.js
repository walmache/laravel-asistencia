// API Configuration
window.API_BASE_URL = window.API_BASE_URL || (document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') || '/api');
window.API_TOKEN = localStorage.getItem('api_token') || null;

// Axios configuration
if (typeof axios !== 'undefined') {
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

    // Login form handlers for all forms
    const loginConfigs = [
        { form: 'loginForm', error: 'loginError', email: 'loginEmail', password: 'loginPassword', isModal: true },
        { form: 'loginFormNav', error: 'loginErrorNav', email: 'loginEmailNav', password: 'loginPasswordNav', isModal: false },
        { form: 'loginFormLanding', error: 'loginErrorLanding', email: 'loginEmailLanding', password: 'loginPasswordLanding', isModal: false }
    ];
    
    if (typeof $ !== 'undefined') {
        loginConfigs.forEach(config => {
            const loginForm = document.getElementById(config.form);
            if (loginForm) {
                $(loginForm).on('submit', async function(e) {
                    e.preventDefault();
                    const errorDiv = $('#' + config.error);
                    errorDiv.addClass('d-none');
                    
                    try {
                        const response = await axios.post(window.API_BASE_URL + '/login', {
                            email: $('#' + config.email).val(),
                            password: $('#' + config.password).val()
                        });
                        
                        if (response.data.token) {
                            localStorage.setItem('api_token', response.data.token);
                            window.API_TOKEN = response.data.token;
                            axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;
                            
                            document.cookie = `laravel_session=${response.data.user?.id || ''}; path=/`;
                            
                            if (config.isModal) {
                                $('#loginModal').modal('hide');
                            } else {
                                const dropdown = document.querySelector('[data-bs-toggle="dropdown"]');
                                if (dropdown) {
                                    const bsDropdown = bootstrap.Dropdown.getInstance(dropdown);
                                    if (bsDropdown) bsDropdown.hide();
                                }
                            }
                            
                            window.location.href = '/dashboard';
                        }
                    } catch (error) {
                        let errorMsg = 'Error al iniciar sesión';
                        if (error.response?.data?.error) {
                            if (typeof error.response.data.error === 'object') {
                                const errors = Object.values(error.response.data.error).flat();
                                errorMsg = errors.join(', ');
                            } else {
                                errorMsg = error.response.data.error;
                            }
                        } else if (error.response?.data?.message) {
                            errorMsg = error.response.data.message;
                        }
                        errorDiv.text(errorMsg).removeClass('d-none');
                    }
                });
            }
        });
        
        $('#loginModal').on('hidden.bs.modal', function() {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) loginForm.reset();
            $('#loginError').addClass('d-none');
        });
    }
});

