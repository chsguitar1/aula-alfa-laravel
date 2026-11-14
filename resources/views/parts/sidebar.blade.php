<aside class="app-sidebar bg-body-secondary border-end">
    <div class="p-3">
        <nav class="nav flex-column gap-2">
            <a href="{{ route('home') }}" class="nav-link">Início</a>
            @auth
                @if (auth()->user()->profile?->type === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="nav-link">Usuários</a>
                    <a href="{{ route('admin.users.create') }}" class="nav-link">Cadastrar usuário</a>
                @endif
            @endauth
        </nav>
    </div>
</aside>
