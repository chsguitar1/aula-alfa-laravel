<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="{{ url('/') }}" class="brand-link">
      <i class="bi bi-shield-lock-fill fs-4 me-2"></i>
      <span class="brand-text fw-light">Rede Patas</span>
    </a>
  </div>

  <div class="sidebar-search" role="search">
    <label for="sidebar-search-input" class="visually-hidden">Filtrar menu</label>
    <input
      type="search"
      id="sidebar-search-input"
      class="form-control form-control-sm"
      placeholder="Filtrar menu…"
      autocomplete="off"
      data-lte-toggle="sidebar-search"
      data-lte-target="#navigation"
    />
    <p class="fs-7 text-secondary mt-2 mb-0" data-lte-search-empty role="status" hidden>
      Nenhuma página encontrada.
    </p>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2" aria-label="Menu principal">
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        data-accordion="true"
        id="navigation"
      >
        <li class="nav-item">
          <a href="{{ route('register') }}" class="nav-link">
            <i class="nav-icon bi bi-person-plus"></i>
            <p>Novo Usuario</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>Listar Usuario</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>