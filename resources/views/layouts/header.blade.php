<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-shadow {{ \App\Helpers\AppHelper::themeClass()['nav_class'] }}">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav">
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item dropdown dropdown-language"><a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ficon" data-feather="{{ \App\Helpers\AppHelper::themeClass()['theme_icon'] }}"></i></a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                    <a class="dropdown-item" href="{{ url('user_theme/0') }}" data-language="0"><i class="ficon" data-feather="sun"></i>Light</a>
                    <a class="dropdown-item" href="{{ url('user_theme/1') }}" data-language="1"><i class="ficon" data-feather="moon"></i>Dark</a>
                    <a class="dropdown-item" href="{{ url('user_theme/2') }}" data-language="2"><i class="ficon" data-feather="sunset"></i>Semi-Dark</a>
                </div>
                </li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder"> {{ auth()->user()->name }} </span><span class="user-status">Admin</span></div><span class="avatar"><img class="round" src="{{ (auth()->user()->profile != null) ? url('/uploads/users/').'/'. auth()->user()->profile : asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="me-50" data-feather="user"></i> Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="me-50" data-feather="power"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>