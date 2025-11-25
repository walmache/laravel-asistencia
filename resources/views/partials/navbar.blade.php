<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="dropdown" href="#">
                @if(app()->getLocale() == 'es')
                    <span class="flag-icon me-1">🇪🇸</span><span>Español</span>
                @else
                    <span class="flag-icon me-1">🇬🇧</span><span>English</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="{{ route('lang.switch', ['locale' => 'es']) }}" class="dropdown-item d-flex align-items-center"><span class="flag-icon me-2">🇪🇸</span><span>Español</span></a></li>
                <li><a href="{{ route('lang.switch', ['locale' => 'en']) }}" class="dropdown-item d-flex align-items-center"><span class="flag-icon me-2">🇬🇧</span><span>English</span></a></li>
            </ul>
        </li>
        
        @auth
        <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="far fa-user"></i> {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li><span class="dropdown-item-text">{{ __('common.profile') }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="#" class="dropdown-item"><i class="fas fa-user me-2"></i>{{ __('common.profile') }}</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>{{ __('common.logout') }}</button>
                    </form>
                </li>
            </ul>
        </li>
        @else
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                <span>Already have an account?</span>
                <span class="ms-2 fw-semibold">Login</span>
                <i class="fas fa-chevron-down ms-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 400px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="login-header">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-white-50">Already have an account?</span>
                        <button type="button" class="btn-close btn-close-white" onclick="document.querySelector('[data-bs-toggle=dropdown]').click()"></button>
                    </div>
                    <div class="login-tabs">
                        <button type="button" class="login-tab active" id="loginTabNav">
                            Login <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                    </div>
                </div>
                <form id="loginFormNav">
                    <div class="p-4">
                        <div id="loginErrorNav" class="alert alert-danger d-none"></div>
                        
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
                            <label for="loginEmailNav" class="form-label fw-semibold">Email address</label>
                            <input type="email" class="form-control modern-input" id="loginEmailNav" name="email" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="loginPasswordNav" class="form-label fw-semibold mb-0">Password</label>
                                <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Forget the password?</a>
                            </div>
                            <input type="password" class="form-control modern-input" id="loginPasswordNav" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn modern-btn-primary w-100 mb-3">
                            Sign in
                        </button>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMeNav" name="remember">
                            <label class="form-check-label" for="rememberMeNav">keep me logged-in</label>
                        </div>
                        
                        <div class="text-center mt-4">
                            <span class="text-muted">New here? </span>
                            <a href="#" class="text-decoration-none fw-semibold" style="color: var(--primary-color);">Join Us</a>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        @endauth
    </ul>
</nav>


