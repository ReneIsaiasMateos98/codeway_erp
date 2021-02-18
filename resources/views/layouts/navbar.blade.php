<nav class="main-header navbar navbar-expand navbar-dark elevation-2">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="nav-link" data-widget="pushmenu">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        {{-- <img src="{{ asset('storage/users/user.png') }}"  class="rounded-circle" alt="{{ Auth::user()->adminlte_image() }}"> --}}
        {{-- <img src="{{ asset('storage/users/' . Auth::user()->adminlte_image() ) }}"  class="rounded-circle" alt="{{ Auth::user()->adminlte_image() }}"> --}}
        <li class="nav-item dropdown">

            <a id="navbarDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
                {{-- <span class="caret"> --}}

                {{-- </span> --}}
            </a>
            <div class="dropdown-menu dropdown-menu-right"  aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">Mi Perfil</a>
                <a class="dropdown-item" href="{{ route('mydepartament') }}">Mi Departamento</a>
                <a class="dropdown-item" href="{{ route('myevent') }}">Mis Eventos</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
            </div>
        </li>
    </ul>
</nav>


{{-- <nav class="main-header navbar navbar-expand navbar-dark elevation-2">
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
</nav> --}}
