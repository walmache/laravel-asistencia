<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(app()->getLocale() == 'es')
                            <span class="flag-icon me-1 small">🇪🇸</span><span>Español</span>
                        @else
                            <span class="flag-icon me-1 small">🇬🇧</span><span>English</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                        <li><a href="#" class="dropdown-item d-flex align-items-center lang-switcher" data-locale="es"><span class="flag-icon me-2 small">🇪🇸</span><span>Español</span></a></li>
                        <li><a href="#" class="dropdown-item d-flex align-items-center lang-switcher" data-locale="en"><span class="flag-icon me-2 small">🇬🇧</span><span>English</span></a></li>
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
        <li class="nav-item">
            <a class="nav-link login-btn-link d-flex align-items-center px-3 py-2 rounded" href="#" data-bs-toggle="modal" data-target="#loginModal" style="background-color: rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                <i class="fas fa-user me-2"></i>
                <span class="me-2">{{ __('common.enter') }}</span>
                <i class="fas fa-chevron-down"></i>
            </a>
        </li>
        @endauth
    </ul>
</nav>


