<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Security Headers -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'neuroTech - Admin')</title>
    <meta name="description" content="@yield('description', 'Panel de administración de neuroTech - Sistema de gestión de asistencias')">
    <meta name="keywords" content="neuroTech, gestión de asistencias, administración, eventos">
    <meta name="author" content="neuroTech">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'neuroTech - Admin')">
    <meta property="og:description" content="@yield('description', 'Panel de administración de neuroTech')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logoNeurotechNegro.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logoNeurotechNegro.png') }}">
    
    <!-- App Meta -->
    <meta name="api-base-url" content="{{ url('/api') }}">
    <meta name="app-locale" content="{{ app()->getLocale() }}">
    
    <!-- Stylesheets - Local -->
    <!-- AdminLTE ya incluye Bootstrap 4, no cargar Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')
    
    <div class="content-wrapper">
        @php
            $pageTitle = trim($__env->yieldContent('page-title'));
        @endphp
        @if(!empty($pageTitle))
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle }}</h1>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @yield('content')
            </div>
        </section>
    </div>
    
    @include('partials.footer')
</div>
@include('partials.login-modal')

<!-- Scripts - Local -->
<!-- AdminLTE ya incluye Bootstrap 4 JS, no cargar Bootstrap 5 -->
<script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/vendor/axios.min.js') }}"></script>
<script src="{{ asset('js/vendor/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/vendor/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/datatables-config.js') }}"></script>
<script>
$(document).ready(function() {
    // Inicializar tooltips con Bootstrap 4 (jQuery)
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@stack('scripts')
</body>
</html>

