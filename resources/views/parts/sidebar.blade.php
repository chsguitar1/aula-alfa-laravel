<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="brand-link">
            <span class="brand-text fw-light">Rede Patas</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="navigation" aria-label="Menu principal">

                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="nav-icon bi bi-house"></i>
                        <p>Início</p>
                    </a>
                </li>

                @if (auth()->check() && auth()->user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Listagem</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>