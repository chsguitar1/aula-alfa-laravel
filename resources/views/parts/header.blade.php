
<header class="app-header navbar navbar-expand bg-dark navbar-dark border-bottom border-danger">
    <div class="container-fluid">

        <a href="{{ route('home') }}"
           class="navbar-brand text-light">
            🩸 Arquivos de Yharnam
        </a>

        <div class="ms-auto d-flex align-items-center gap-3">

            <span class="text-light">
                Caçador: {{ auth()->user()->name }}
            </span>

            <form action="{{ route('logout') }}"
                  method="POST"
                  class="m-0">
                @csrf

                <button type="submit"
                        class="btn btn-outline-danger btn-sm">
                    Sair da Caçada
                </button>
            </form>

        </div>
    </div>
</header>
