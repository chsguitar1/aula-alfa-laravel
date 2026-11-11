<aside class="app-sidebar shadow">
    <a href="{{ route('home') }}" class="sidebar-brand">
        <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" />
        <span class="fw-semibold">Rede Patas</span>
    </a>

    <div class="sidebar-wrapper">
        <div class="mb-3">
            <input
                type="search"
                class="form-control form-control-sm rounded-pill bg-dark text-light border-secondary px-3"
                placeholder="Filter menu..."
            />
        </div>

        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link text-light">
                    <i class="bi bi-people me-2"></i>
                    <span>Listar Usuario</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
