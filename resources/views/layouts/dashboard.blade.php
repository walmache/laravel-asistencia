<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'neuroTech - Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoNeurotechNegro.png') }}">
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Panel de administración de neuroTech')">
    <meta name="keywords" content="neuroTech, gestión de asistencias, administración">
    <meta name="author" content="neuroTech">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Security Headers -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';">
    
    <!-- Stylesheets - Local -->
    <!-- Bootstrap 4 incluido en AdminLTE, no cargar Bootstrap 5 -->
    <link href="{{ asset('css/vendor/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <meta name="api-base-url" content="{{ url('/api') }}">
    @stack('styles')
</head>
<body>
    @php
        $current_user = auth()->user();
    @endphp
    
    <!-- Header -->
    <header class="navbar navbar-dark sticky-top bg-primary flex-md-nowrap p-0 shadow-sm">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 bg-primary" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logoNeurotechNegro.png') }}" alt="neuroTech" class="me-2 logo-img">
            <span class="fw-bold">neuroTech</span>
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="navbar-nav">
            <div class="nav-item text-nowrap d-flex align-items-center">
                <div class="dropdown me-3">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="langDropdown" role="button" data-toggle="dropdown">
                        @if(app()->getLocale() == 'es')
                            <span class="flag-icon me-1">🇪🇸</span><span>Español</span>
                        @else
                            <span class="flag-icon me-1">🇬🇧</span><span>English</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item d-flex align-items-center lang-switcher" href="#" data-locale="es"><span class="flag-icon me-2">🇪🇸</span><span>Español</span></a></li>
                        <li><a class="dropdown-item d-flex align-items-center lang-switcher" href="#" data-locale="en"><span class="flag-icon me-2">🇬🇧</span><span>English</span></a></li>
                    </ul>
                </div>
                
                @if($current_user)
                <div class="dropdown me-3">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ $current_user->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>{{ __('common.profile') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>{{ __('common.logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('common.login') }}</a>
                @endif
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>{{ __('common.dashboard') }}
                            </a>
                        </li>
                        
                        @if($current_user && $current_user->hasRole(['admin', 'coordinator', 'user']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                <i class="fas fa-clipboard-check me-2"></i>
                                @if($current_user->hasRole('user'))
                                    {{ __('common.my_events') }}
                                @else
                                    {{ __('common.attendance') }}
                                @endif
                            </a>
                        </li>
                        @endif
                        
                        @if($current_user && $current_user->hasRole(['admin', 'coordinator']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('events*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                                <i class="fas fa-calendar-alt me-2"></i>{{ __('common.events') }}
                            </a>
                        </li>
                        @endif
                        
                        @if($current_user && $current_user->hasRole(['admin', 'coordinator']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-users me-2"></i>{{ __('common.users') }}
                            </a>
                        </li>
                        @endif
                        
                        @if($current_user && $current_user->hasRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('organizations*') ? 'active' : '' }}" href="{{ route('organizations.index') }}">
                                <i class="fas fa-building me-2"></i>{{ __('common.organizations') }}
                            </a>
                        </li>
                        @endif
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                        <span>{{ __('common.services') }}</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('landing') }}">
                                <i class="fas fa-home me-2"></i>{{ __('common.home') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts - Local -->
    <!-- Bootstrap 4 JS incluido en AdminLTE, no cargar Bootstrap 5 -->
    <script src="{{ asset('js/vendor/axios.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>


