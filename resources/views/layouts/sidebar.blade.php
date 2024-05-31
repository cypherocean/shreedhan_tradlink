<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="brand-logo">
                        <img src="{{ asset('app-assets/images/logo/logo.png') }}" width="400px" alt="logo">
                    </span>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ Request::is('dashboard*') ? 'active nav-item' : 'nav-item' }}">
                <a class="d-flex align-items-center" href="dashboard-analytics.html">
                    <i data-feather="home"></i>
                    <span class="menu-item text-truncate" data-i18n="Dashboards">Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('users*') ? 'active nav-item' : 'nav-item' }}">
                <a class="d-flex align-items-center" href="{{ route('users') }}">
                    <i data-feather='users'></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Users</span>
                </a>
            </li>
            <li class="{{ Request::is('recharge*') ? 'active nav-item' : 'nav-item' }}">
                <a class="d-flex align-items-center" href="{{ route('recharge') }}">
                    <i data-feather='dollar-sign'></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Recharge</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->