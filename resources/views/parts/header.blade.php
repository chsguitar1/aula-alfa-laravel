<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">

    {{-- Links do lado esquerdo --}}
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Alternar menu lateral">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="#" class="nav-link">
          <i class="bi bi-grid-1x2 me-1" aria-hidden="true"></i>
          Live preview
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="#" class="nav-link">
          <i class="bi bi-book me-1" aria-hidden="true"></i>
          Documentation
        </a>
      </li>
    </ul>

    {{-- Busca --}}
    <form class="navbar-search d-none d-md-block ms-3" role="search" action="#">
      <label for="navbar-search-input" class="visually-hidden">Buscar</label>
      <div class="navbar-search-field">
        <input
          type="search"
          id="navbar-search-input"
          name="q"
          class="form-control"
          placeholder="Search…"
          autocomplete="off"
        />
        <button class="navbar-search-submit" type="submit" aria-label="Buscar">
          <i class="bi bi-search" aria-hidden="true"></i>
        </button>
      </div>
    </form>

    {{-- Links do lado direito --}}
    <ul class="navbar-nav ms-auto">

      {{-- Mensagens (exemplo do AdminLTE, sem imagens) --}}
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Mensagens: 3 não lidas">
          <i class="bi bi-chat-text"></i>
          <span class="navbar-badge badge text-bg-danger">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <a href="#" class="dropdown-item">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="bi bi-person-circle fs-2 me-3"></i>
              </div>
              <div class="flex-grow-1">
                <p class="dropdown-item-title">Brad Diesel</p>
                <p class="fs-7">Call me whenever you can...</p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>

      {{-- Notificações (exemplo do AdminLTE) --}}
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Notificações: 15 não lidas">
          <i class="bi bi-bell-fill"></i>
          <span class="navbar-badge badge text-bg-warning">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-envelope me-2"></i> 4 new messages
            <span class="float-end text-secondary fs-7">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>

      {{-- Tela cheia --}}
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen" aria-label="Tela cheia">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
        </a>
      </li>

      {{-- Tema claro / escuro --}}
      <li class="nav-item dropdown">
        <a
          class="nav-link"
          href="#"
          id="bd-theme"
          aria-label="Alternar tema"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
          <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
          <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme" style="--bs-dropdown-min-width: 8rem">
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
              <i class="bi bi-sun-fill me-2"></i> Light
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
              <i class="bi bi-moon-fill me-2"></i> Dark
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
              <i class="bi bi-circle-half me-2"></i> Auto
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
        </ul>
      </li>

      {{-- Menu do usuário --}}
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle fs-4 me-1"></i>
          <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header text-bg-primary">
            <i class="bi bi-person-circle display-3"></i>
            <p>
              {{ auth()->user()->name }}
              <small>{{ auth()->user()->email }}</small>
            </p>
          </li>
          <li class="user-footer">
            {{-- Logout por POST com proteção CSRF (nunca por link GET). --}}
            <form action="{{ route('logout') }}" method="POST" class="d-grid">
              @csrf
              <button type="submit" class="btn btn-outline-danger">Sair</button>
            </form>
          </li>
        </ul>
      </li>

    </ul>
  </div>
</nav>