<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg login-modal-enhanced">
            <div class="login-modal-header">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/logoNeurotechNegro.png') }}" alt="neuroTech" class="logo-img me-2" style="width: 45px; height: 45px;">
                        <span class="fs-5 fw-semibold text-white">neuroTech</span>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form id="loginForm">
                <div class="modal-body p-4">
                    <div id="loginError" class="alert alert-danger d-none mb-3"></div>
                    
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text login-input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control login-input-enhanced" id="loginEmail" name="email" placeholder="Enter your email" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text login-input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control login-input-enhanced" id="loginPassword" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn w-100 login-submit-btn-enhanced mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>LOGIN
                    </button>
                    
                    <div class="text-center mb-3">
                        <a href="#" class="text-decoration-none login-forgot-link">Forgot password?</a>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


