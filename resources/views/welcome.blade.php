
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | MeuSistema</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <span class="logo-icon">M</span>
            <span>MeuSistema</span>
        </div>

        <nav>
            @auth
                <span class="nav-user">
                    {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    Entrar
                </a>
            @endauth
        </nav>
    </header>

    <main class="main-content">
        <div class="welcome-card">

            @auth
                <span class="status">
                    ● Sessão autenticada
                </span>

                <h1>
                    Bem-vindo de volta,
                    <span>{{ Auth::user()->name }}!</span>
                </h1>

                <p>
                    Você entrou com sucesso no sistema.
                    Aqui você pode acessar suas informações
                    de forma simples e segura.
                </p>

                <div class="user-info">
                    <div class="avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <strong>{{ Auth::user()->name }}</strong>
                        <small>{{ Auth::user()->email }}</small>
                    </div>
                </div>

            @else
                <span class="status">
                    Bem-vindo ao sistema
                </span>

                <h1>
                    Sua experiência começa
                    <span>aqui.</span>
                </h1>

                <p>
                    Faça login ou crie sua conta para acessar
                    as funcionalidades do sistema.
                </p>

                <div class="actions">
                    <a href="{{ route('login') }}" class="btn-primary">
                        Fazer login
                    </a>

                    <a href="{{ url('/register') }}" class="btn-secondary">
                        Criar conta
                    </a>
                </div>
            @endauth

        </div>
        <!-- /.Start col -->

        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">✓</div>
                <h3>Segurança</h3>
                <p>Autenticação protegida por sessão.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">◎</div>
                <h3>Gerenciamento</h3>
                <p>Organização de usuários e permissões.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">◇</div>
                <h3>Simplicidade</h3>
                <p>Uma interface moderna e intuitiva.</p>
            </div>
        </section>
    </main>

    <footer>
        <p>© {{ date('Y') }} MeuSistema. Todos os direitos reservados.</p>
    </footer>

</body>
</html>
