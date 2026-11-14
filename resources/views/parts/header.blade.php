<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">Início</a>
        <div class="dropdown ms-auto">
            <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">{{ auth()->user()->name }}</button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Sair</button>
                </form></li>
            </ul>
        </div>
    </div>
</nav>
