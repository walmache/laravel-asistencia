<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>neuroTech - Sistema Inteligente de Gestión de Asistencias</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoNeurotechNegro.png') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
                <img src="{{ asset('images/logoNeurotechNegro.png') }}" alt="neuroTech" class="logo-img logo-lg">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="#inicio">{{ __('common.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="#servicios">{{ __('common.services') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="#testimonios">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="#contacto">{{ __('common.contact') }}</a>
                    </li>
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(app()->getLocale() == 'es')
                                <span class="flag-icon me-1">🇪🇸</span><span>Español</span>
                            @else
                                <span class="flag-icon me-1">🇬🇧</span><span>English</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', ['locale' => 'es']) }}"><span class="flag-icon me-2">🇪🇸</span><span>Español</span></a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', ['locale' => 'en']) }}"><span class="flag-icon me-2">🇬🇧</span><span>English</span></a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link text-white d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <span>Already have an account?</span>
                            <span class="ms-2 fw-semibold">Login</span>
                            <i class="fas fa-chevron-down ms-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 400px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border-radius: 1rem; overflow: hidden;">
                            <div class="login-header">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-white-50">Already have an account?</span>
                                    <button type="button" class="btn-close btn-close-white" onclick="document.querySelector('[data-bs-toggle=dropdown]').click()"></button>
                                </div>
                                <div class="login-tabs">
                                    <button type="button" class="login-tab active" id="loginTabLanding">
                                        Login <i class="fas fa-chevron-down ms-1"></i>
                                    </button>
                                </div>
                            </div>
                            <form id="loginFormLanding">
                                <div class="p-4">
                                    <div id="loginErrorLanding" class="alert alert-danger d-none"></div>
                                    
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
                                        <label for="loginEmailLanding" class="form-label fw-semibold">Email address</label>
                                        <input type="email" class="form-control modern-input" id="loginEmailLanding" name="email" required autofocus>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="loginPasswordLanding" class="form-label fw-semibold mb-0">Password</label>
                                            <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Forget the password?</a>
                                        </div>
                                        <input type="password" class="form-control modern-input" id="loginPasswordLanding" name="password" required>
                                    </div>
                                    
                                    <button type="submit" class="btn modern-btn-primary w-100 mb-3">
                                        Sign in
                                    </button>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMeLanding" name="remember">
                                        <label class="form-check-label" for="rememberMeLanding">keep me logged-in</label>
                                    </div>
                                    
                                    <div class="text-center mt-4">
                                        <span class="text-muted">New here? </span>
                                        <a href="#" class="text-decoration-none fw-semibold" style="color: var(--primary-color);">Join Us</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="position-relative hero-section">
        <div class="container">
            <div class="row align-items-center text-white">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-3 fw-bold mb-4">Sistema Inteligente de Gestión de Asistencias</h1>
                    <p class="lead mb-4">Revoluciona la forma en que gestionas las asistencias de tus eventos con tecnología de reconocimiento facial, códigos QR y más. Simple, rápido y eficiente.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg rounded-pill px-5">
                            <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                        </a>
                        <a href="#servicios" class="btn btn-outline-light btn-lg rounded-pill px-5">
                            <i class="fas fa-info-circle me-2"></i>Conocer Más
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicios" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3">Nuestros Servicios</h2>
                <p class="lead text-muted">Tecnología avanzada para una gestión eficiente de asistencias</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-primary bg-gradient rounded-3 p-4 mb-3 d-inline-block">
                                <i class="fas fa-user-check fa-2x text-white"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Reconocimiento Facial</h3>
                            <p class="card-text text-muted">Tecnología de inteligencia artificial para registro automático de asistencias mediante reconocimiento facial. Rápido, seguro y preciso.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-success bg-gradient rounded-3 p-4 mb-3 d-inline-block">
                                <i class="fas fa-qrcode fa-2x text-white"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Códigos QR</h3>
                            <p class="card-text text-muted">Registro instantáneo de asistencias mediante escaneo de códigos QR. Ideal para eventos masivos y registro rápido.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-info bg-gradient rounded-3 p-4 mb-3 d-inline-block">
                                <i class="fas fa-barcode fa-2x text-white"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Códigos de Barras</h3>
                            <p class="card-text text-muted">Solución tradicional y confiable para el registro de asistencias mediante códigos de barras. Compatible con cualquier lector.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-warning bg-gradient rounded-3 p-4 mb-3 d-inline-block">
                                <i class="fas fa-users-cog fa-2x text-white"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Gestión de Usuarios</h3>
                            <p class="card-text text-muted">Administra usuarios, roles y permisos de manera eficiente. Control total sobre quién puede acceder y gestionar eventos.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-danger bg-gradient rounded-3 p-4 mb-3 d-inline-block">
                                <i class="fas fa-calendar-alt fa-2x text-white"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Gestión de Eventos</h3>
                            <p class="card-text text-muted">Crea y gestiona eventos de manera sencilla. Asigna usuarios, configura métodos de registro y monitorea asistencias en tiempo real.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-secondary bg-gradient rounded-3 p-4 mb-3 d-inline-block">
                                <i class="fas fa-chart-line fa-2x text-white"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3">Reportes y Analytics</h3>
                            <p class="card-text text-muted">Visualiza estadísticas detalladas de asistencias, genera reportes y analiza el rendimiento de tus eventos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonios" class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container py-5">
            <div class="text-center mb-5 text-white">
                <h2 class="display-4 fw-bold mb-3">Testimonios</h2>
                <p class="lead">Lo que dicen nuestros clientes</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">"Sistema increíble, ha simplificado completamente la gestión de asistencias en nuestros eventos corporativos."</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <span class="text-white fw-bold">JD</span>
                                </div>
                                <div>
                                    <strong>Juan Díaz</strong>
                                    <p class="text-muted mb-0 small">CEO, TechCorp</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">"El reconocimiento facial es impresionante. Nuestros eventos ahora son mucho más eficientes y profesionales."</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <span class="text-white fw-bold">MG</span>
                                </div>
                                <div>
                                    <strong>María González</strong>
                                    <p class="text-muted mb-0 small">Directora de Eventos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">"La mejor inversión que hemos hecho. Ahorramos horas de trabajo manual y nuestros reportes son perfectos."</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <span class="text-white fw-bold">CL</span>
                                </div>
                                <div>
                                    <strong>Carlos López</strong>
                                    <p class="text-muted mb-0 small">Gerente de Operaciones</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="py-5 text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="container text-center py-5">
            <h2 class="display-4 fw-bold mb-3">{{ __('common.contact') }}</h2>
            <p class="lead mb-4">¿Tienes preguntas? ¿Necesitas más información? Estamos aquí para ayudarte.</p>
            <a href="mailto:contacto@neurotech.com" class="btn btn-light btn-lg rounded-pill px-5">
                <i class="fas fa-envelope me-2"></i>Contáctanos
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} neuroTech. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>

