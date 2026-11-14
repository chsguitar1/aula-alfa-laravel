<header class="app-header border-bottom bg-white">
    <div class="container-fluid d-flex align-items-center justify-content-between py-3">
        <a href="{{ route('home') }}" class="navbar-brand mb-0 h1">Sistema</a>

        @auth
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Sair</button>
                </form>
            </div>
        @else
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Entrar</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">Cadastre-se</a>
            </div>
        @endauth
    </div>
</header>
