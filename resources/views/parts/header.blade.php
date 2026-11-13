<header class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <a href="{{ route('home') }}" class="navbar-brand">Sistema</a>
    <div class="ms-auto">
      @auth
        <span class="me-3">{{ auth()->user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-outline-secondary btn-sm">Sair</button>
        </form>
      @endauth
    </div>
  </div>
</header>
