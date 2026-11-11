<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav align-items-center gap-2">
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('home') }}" class="nav-link">Live preview</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Documentation</a>
            </li>
            <li class="nav-item d-none d-md-block ms-2">
                <input
                    type="search"
                    class="form-control form-control-sm rounded-pill bg-dark text-light border-secondary px-3"
                    placeholder="Search..."
                    style="width: 220px;"
                />
            </li>
        </ul>

        <ul class="navbar-nav ms-auto align-items-center gap-3">
            <li class="nav-item">
                <a class="nav-link position-relative" href="#">
                    <span class="badge rounded-pill text-bg-danger">3</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link position-relative" href="#">
                    <span class="badge rounded-pill text-bg-warning">15</span>
                </a>
            </li>

            @auth
                <li class="nav-item d-flex align-items-center">
                    <img
                        src="{{ asset('assets/img/user2-160x160.jpg') }}"
                        class="user-image rounded-circle shadow-sm"
                        alt="User Image"
                    />
                    <span class="text-light me-3">{{ Auth::user()->name }}</span>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3">Sair</button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>
