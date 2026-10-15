<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AdminLTE | Recuperação de Senha</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    >

    <style>
        body {
            font-family: 'Source Sans 3', sans-serif;
            background-color: #e9ecef;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            text-align: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .login-logo a {
            color: #343a40;
            text-decoration: none;
        }

        .login-card-body {
            padding: 25px;
        }

        .login-box-msg {
            margin-bottom: 20px;
            color: #6c757d;
        }
    </style>
</head>

<body class="login-page">

<main class="login-box">

    <h1 class="login-logo">
        <a href="{{ url('/') }}"><b>Admin</b>LTE</a>
    </h1>

    <div class="card shadow-sm border-0">
        <div class="card-body login-card-body">

            <p class="login-box-msg">
                Esqueceu sua senha? Informe seu e-mail e cadastre uma nova senha.
            </p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('senha.alterar') }}" method="POST">
                @csrf

                <label class="visually-hidden" for="email">E-mail</label>

                <div class="input-group mb-3">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="E-mail"
                        value="{{ old('email') }}"
                        required
                    >

                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                </div>

                <label class="visually-hidden" for="password">
                    Nova senha
                </label>

                <div class="input-group mb-3">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Nova senha"
                        required
                    >

                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                </div>

                <label class="visually-hidden" for="password_confirmation">
                    Confirmar senha
                </label>

                <div class="input-group mb-3">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirmar nova senha"
                        required
                    >

                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        Alterar senha
                    </button>
                </div>
            </form>

            <p class="mt-3 mb-1">
                <a href="{{ url('/login') }}">Voltar para o login</a>
            </p>

            <p class="mb-0">
                <a href="{{ url('/register') }}">
                    Cadastrar novo usuário
                </a>
            </p>

        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
