<aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="brand-link">
            <span class="brand-text fw-light">Rede Pistas</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Início</p>
                    </a>
                </li>
               @if(auth()->user()->profile && auth()->user()->profile->type === 'admin')
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>Listar Usuários</p>
        </a>
    </li>
@endif
            </ul>
        </nav> 
    </div>
</aside>