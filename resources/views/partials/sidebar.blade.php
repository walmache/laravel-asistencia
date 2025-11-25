<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logoNeurotechNegro.png') }}" alt="neuroTech" class="brand-image elevation-3 logo-img">
        <span class="brand-text font-weight-light">neuroTech</span>
    </a>
    
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
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
</aside>


