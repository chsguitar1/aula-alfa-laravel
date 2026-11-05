<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Alternar menu lateral">
          <i class="bi bi-list"></i>
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto align-items-center">
      {{-- Menu do usuário autenticado: nome + botão Sair sempre visível --}}
      <li class="nav-item user-menu d-flex align-items-center gap-2">
        <span class="navbar-text" title="{{ auth()->user()->email }}">{{ auth()->user()->name }}</span>
        {{-- Logout é POST com CSRF; nunca um link GET. --}}
        <form action="{{ route('logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-sm">Sair</button>
        </form>
      </li>
    </ul>
  </div>
</nav>