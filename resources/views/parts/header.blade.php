<header class="app-header">
    <nav class="navbar navbar-expand bg-body">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>

            @auth
                <div class="d-flex align-items-center gap-3 ms-auto">
                    @if (auth()->user()->profile?->type === 'admin')
                        <a href="{{ route('admin.users.index') }}" class="nav-link">Usuários</a>
                    @endif
                    <span class="text-body-secondary">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Sair</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>
</header>
