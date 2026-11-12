<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-wrapper p-3">
        <nav>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Início</a></li>
                @if (auth()->user()?->profile?->type === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Lista de usuários</a></li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
