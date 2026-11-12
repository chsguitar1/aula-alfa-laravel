<header class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">Painel Administrativo</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            @auth
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Sair</button>
                </form>
            @endauth
        </div>
    </div>
</header>
