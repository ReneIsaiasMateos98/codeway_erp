<nav class="main-header navbar navbar-expand navbar-dark elevation-2">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="nav-link" data-widget="pushmenu">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </li>
        <li class="nav-item">
            <img src="{{ asset('storage/users/user.png') }}" width="9%" class="rounded-circle" alt="{{ Auth::user()->id }}">
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">{{ Auth::user()->name }}</a>
        </li>
    </ul>
</nav>
