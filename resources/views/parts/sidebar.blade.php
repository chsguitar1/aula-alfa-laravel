<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-text fw-light">{{ config('app.name') }}</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="Navegação principal">
            <ul class="nav sidebar-menu flex-column" role="menu">
                @if (auth()->user()?->profile?->type === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <span class="nav-icon bi bi-people"></span>
                            <p>Usuários</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
