<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-text fw-light">Aula Alfa Laravel</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="menu">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <p>Início</p>
                    </a>
                </li>

                @if(auth()->user()?->profile?->type === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <p>Gerenciar Usuários</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
