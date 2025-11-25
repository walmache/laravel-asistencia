<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
            <div class="login-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-white-50">Already have an account?</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="login-tabs">
                    <button type="button" class="login-tab active" id="loginTab">
                        Login <i class="fas fa-chevron-down ms-1"></i>
                    </button>
                </div>
            </div>
            <form id="loginForm">
                <div class="modal-body p-4">
                    <div id="loginError" class="alert alert-danger d-none"></div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-3">Login via</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="social-login-btn btn-facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="social-login-btn btn-twitter">
                                    <i class="fab fa-twitter"></i> Twitter
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="login-separator">
                        <span class="text-muted bg-white px-2">or</span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label fw-semibold">Email address</label>
                        <input type="email" class="form-control modern-input" id="loginEmail" name="email" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="loginPassword" class="form-label fw-semibold mb-0">Password</label>
                            <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Forget the password?</a>
                        </div>
                        <input type="password" class="form-control modern-input" id="loginPassword" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn modern-btn-primary w-100 mb-3">
                        Sign in
                    </button>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">keep me logged-in</label>
                    </div>
                    
                    <div class="text-center mt-4">
                        <span class="text-muted">New here? </span>
                        <a href="#" class="text-decoration-none fw-semibold" style="color: var(--primary-color);">Join Us</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


