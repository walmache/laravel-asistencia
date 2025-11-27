<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Security Headers -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'neuroTech - Admin')</title>
    <meta name="description" content="@yield('description', 'Panel de administración de neuroTech - Sistema de gestión de asistencias')">
    <meta name="author" content="neuroTech">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logoNeurotechNegro.png') }}">
    
    <!-- App Meta -->
    <meta name="api-base-url" content="{{ url('/api') }}">
    <meta name="app-locale" content="{{ app()->getLocale() }}">
    
    <!-- AdminLTE 4.0 CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatables/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<!--begin::App Wrapper-->
<div class="app-wrapper">
    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand navbar-dark bg-dark">
        <div class="container-fluid">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            
            <!-- Right navbar links -->
            <ul class="navbar-nav ms-auto">
                <!-- Language Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        @if(app()->getLocale() == 'es')
                            🇪🇸 Español
                        @else
                            🇬🇧 English
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a href="#" class="dropdown-item lang-switcher" data-locale="es">🇪🇸 Español</a></li>
                        <li><a href="#" class="dropdown-item lang-switcher" data-locale="en">🇬🇧 English</a></li>
                    </ul>
                </li>
                
                <!-- User Menu -->
                @auth
                <li class="nav-item dropdown user-menu me-3">
                    <a href="#" class="nav-link dropdown-toggle p-1" data-bs-toggle="dropdown" title="{{ auth()->user()->name }}">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle border border-2 border-white" style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                            <i class="fas fa-circle-user fa-2x"></i>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary text-center py-3">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle shadow border border-2 border-white" style="width: 90px; height: 90px; object-fit: cover;">
                            @else
                                <i class="fas fa-circle-user fa-5x text-white-50"></i>
                            @endif
                            <p class="mt-2 mb-0">
                                {{ auth()->user()->name }}
                                <small class="d-block">{{ auth()->user()->email }}</small>
                                <span class="badge bg-light text-dark mt-1">{{ ucfirst(auth()->user()->role) }}</span>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">{{ __('common.profile') }}</a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline float-end">
                                @csrf
                                <button type="submit" class="btn btn-default btn-flat">{{ __('common.logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt"></i> {{ __('common.enter') }}
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </nav>
    <!--end::Header-->
    
    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="{{ asset('images/logoNeurotechNegro.png') }}" alt="neuroTech" class="brand-image opacity-75 shadow">
                <span class="brand-text fw-light">neuroTech</span>
            </a>
        </div>
        <!--end::Sidebar Brand-->
        
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('common.dashboard') }}</p>
                        </a>
                    </li>
                    
                    @auth
                    @if(auth()->user()->hasRole(['admin', 'coordinator', 'user']))
                    <li class="nav-item">
                        <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>{{ auth()->user()->hasRole('user') ? __('common.my_events') : __('common.attendance') }}</p>
                        </a>
                    </li>
                    @endif
                    
                    @if(auth()->user()->hasRole(['admin', 'coordinator']))
                    <li class="nav-item">
                        <a href="{{ route('events.index') }}" class="nav-link {{ request()->is('events*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>{{ __('common.events') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('common.users') }}</p>
                        </a>
                    </li>
                    @endif
                    
                    @if(auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a href="{{ route('organizations.index') }}" class="nav-link {{ request()->is('organizations*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>{{ __('common.organizations') }}</p>
                        </a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </nav>
        </div>
        <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->
    
    <!--begin::App Main-->
    <main class="app-main">
        @php
            $pageTitle = trim($__env->yieldContent('page-title'));
        @endphp
        @if(!empty($pageTitle))
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">{{ $pageTitle }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <!--end::App Content Header-->
        @endif
        
        <!--begin::App Content-->
        <div class="app-content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->
    
    <!--begin::Footer-->
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">AdminLTE 4.0</div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}" class="text-decoration-none">neuroTech</a>.</strong>
        Todos los derechos reservados.
    </footer>
    <!--end::Footer-->
</div>
<!--end::App Wrapper-->

@include('partials.login-modal')

<!-- Scripts -->
<script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/vendor/axios.min.js') }}"></script>
<script src="{{ asset('datatables/js/dataTables.min.js') }}"></script>
<script src="{{ asset('datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/datatables-config.js') }}"></script>
<script src="{{ asset('js/tooltip-init.js') }}"></script>
@stack('scripts')
</body>
</html>
