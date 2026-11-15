<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-text fw-light">Rede Patas</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="navigation" aria-label="Menu principal">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link @if (request()->routeIs('home')) active @endif">
                        <i class="nav-icon bi bi-house"></i>
                        <p>Início</p>
                    </a>
                </li>

                {{-- Esconder o link é só conveniência: quem protege /admin/* é o middleware "admin". --}}
                @if (auth()->user()?->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link @if (request()->routeIs('admin.users.*', 'admin.user.*')) active @endif">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Listar Usuário</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">
                            <i class="nav-icon bi bi-person-plus"></i>
                            <p>Cadastrar Usuário</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
