
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Gerenciamento de Usuários' }}</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <div class="logo-icon">A</div>

            <div>
                <strong>AdminPanel</strong>
                <small>Área administrativa</small>
            </div>
        </div>

        <div class="nav-actions">
            <span class="admin-name">
                {{ Auth::user()->name }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    Sair
                </button>
            </form>
        </div>
    </header>

    <main class="container">

        <div class="page-header">
            <div>
                <span class="subtitle">PAINEL ADMINISTRATIVO</span>
                <h1>Gerenciamento de usuários</h1>
                <p>Visualize e gerencie os usuários cadastrados.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-create">
                + Cadastrar usuário
            </a>
        </div>

        <section class="stats">
            <div class="stat-card">
                <span>Total de usuários</span>
                <h2>{{ $users->total() }}</h2>
                <p>Contas cadastradas no sistema</p>
            </div>
        </section>

        <section class="table-card">

            <div class="table-header">
                <div>
                    <h2>Usuários cadastrados</h2>
                    <p>Lista de contas registradas</p>
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <span class="id-badge">
                                        #{{ $user->id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="user-cell">
                                        <div class="avatar">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    <a
                                        href="{{ url('/admin/user/' . $user->id) }}"
                                        class="btn-view"
                                    >
                                        Visualizar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $users->links() }}
            </div>

        </section>
    </main>

    <footer>
        <p>
            © {{ date('Y') }} AdminPanel.
            Sistema de gerenciamento de usuários.
        </p>
    </footer>

</body>
</html>
