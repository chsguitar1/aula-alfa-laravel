<aside class="app-sidebar bg-body-secondary shadow">
    <div class="sidebar-brand"><a href="{{ url('/') }}" class="brand-link">Sistema de usuários</a></div>
    <div class="sidebar-wrapper"><nav class="p-3">
        <a class="d-block mb-3" href="{{ url('/') }}">Início</a>
        @if (auth()->user()->profile?->type === 'admin')
            <a class="d-block mb-3" href="{{ route('admin.users.index') }}">Usuários</a>
            <a class="d-block" href="{{ route('register') }}">Cadastrar usuário</a>
        @endif
    </nav></div>
</aside>
