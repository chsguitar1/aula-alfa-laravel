<!doctype html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login | Rede Patas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/adminlte-custom.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Entre com seu email e senha</p>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-info alert-dismissible fade show py-2 small" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST" novalidate>
                    @csrf

                    <div class="input-group mb-3">
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Senha"
                            required
                        />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>

                <p class="mb-0 mt-3 text-center small">
                    Ainda não tem uma conta?
                    <a href="{{ route('register') }}" class="text-center">Cadastre-se</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
