<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="{{ route('home') }}" class="brand-link">
      <span class="brand-text fw-light">Rede Patas</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" role="menu">
        {{-- Itens administrativos só aparecem para admin; a proteção real é o middleware. --}}
        @if (auth()->user()->isAdmin())
          <li class="nav-item">
            <a href="{{ route('admin.users.index') }}"
               class="nav-link @if (request()->routeIs('admin.users.*')) active @endif">
              <i class="nav-icon bi bi-people"></i>
              <p>Listar Usuario</p>
            </a>
          </li>
        @endif
      </ul>
    </nav>
  </div>
</aside>
