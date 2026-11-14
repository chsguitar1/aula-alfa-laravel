<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="{{ url('/') }}" class="nav-link">Início</a></li>
        </ul>

        <ul class="navbar-nav ms-auto">
            @auth
                {{-- Menu do usuário --}}
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-secondary">{{ auth()->user()->email }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            {{-- Logout é POST com CSRF; nunca um link GET. --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Sair</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </div>
</nav>
