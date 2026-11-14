
<header class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <a href="{{ route('home') }}" class="navbar-brand">
            Área Administrativa
        </a>

        @auth
            <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-danger">
                    Sair
                </button>
            </form>
        @endauth
    </div>
</header>
