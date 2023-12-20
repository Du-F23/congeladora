<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        @if(Auth::check())
            <li class="nav-item">
                <a class="nav-link text-center items-center" href="{{ route('dashboard') }}">
                    <i class="icon-grid menu-icon"></i>
                    <span class="menu-title text-center">{{ __('Dashboard')}}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('teams.*') ? 'active' : '' }}">
                <a class="nav-link text-center items-center" href="{{ route('teams.index') }}">
                    <i class="icon-grid mr-2 ti ti-users-group"></i>
                    <span class="menu-title text-center">{{ __('Teams')}}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('matches.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('matches.index') }}">
                    <i class="icon-layout menu-icon"></i>
                    <span class="menu-title">{{ __('Soccer Matches') }}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('scores.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('scores.index') }}">
                    <i class="icon-bar-graph menu-icon"></i>
                    <span class="menu-title">{{ __('Scores') }}</span>
                </a>
            </li>
            @if(Auth::user()->rol_id === 1)
                <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <i class="ti ti-user menu-icon"></i>
                        <span class="menu-title">{{ __('Users') }}</span>
                    </a>
                </li>
            @endif
        @else
            <li class="nav-item {{ request()->routeIs('welcome') ? 'active' : ''  }}">
                <a class="nav-link" href="{{ route('welcome') }}">
                    <i class="icon-columns menu-icon"></i>
                    <span class="menu-title">{{ __('Soccer Matches') }}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('scores.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('scores.index') }}">
                    <i class="icon-bar-graph menu-icon"></i>
                    <span class="menu-title">{{ __('Scores') }}</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
