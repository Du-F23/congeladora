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
            <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="ti ti-user menu-icon"></i>
                    <span class="menu-title">{{ __('Users') }}</span>
                </a>
            </li>
        @else
        <li class="nav-item {{ route('welcome') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                <i class="icon-columns menu-icon"></i>
                <span class="menu-title">{{ __('Soccer Matches') }}</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('matches.index') }}">{{ __('Soccer Matches') }}</a></li>
                </ul>
            </div>
        </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#score" aria-expanded="false" aria-controls="scores">
                    <i class="icon-bar-graph menu-icon"></i>
                    <span class="menu-title">{{ __('Scores') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="score">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="">{{ __('Scores') }}</a></li>
                    </ul>
                </div>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Tables</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                <i class="icon-contract menu-icon"></i>
                <span class="menu-title">Icons</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
                <i class="icon-ban menu-icon"></i>
                <span class="menu-title">Error pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="error">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="pages/documentation/documentation.html">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Documentation</span>
            </a>
        </li>
    </ul>
</nav>
