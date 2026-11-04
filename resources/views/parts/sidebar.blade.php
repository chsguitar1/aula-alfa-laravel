<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-text fw-light">Rede Patas</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="navigation" data-lte-toggle="treeview">
                {{-- Esconder o link é só conveniência de UI; a proteção real é o middleware EnsureAdmin. --}}
                @if (auth()->user()?->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Listar Usuario</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
