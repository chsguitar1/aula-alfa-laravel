<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <a href="{{ route('home') }}" class="navbar-brand">
            Aula Alfa Laravel
        </a>

        <ul class="navbar-nav ms-auto">
            @auth
                <li class="nav-item me-3">
                    <span class="nav-link">
                        {{ auth()->user()->name }}
                    </span>
                </li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Sair
                        </button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>
