<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    @vite('resources/scss/app.scss')
</head>
<body class="login-page bg-body-secondary">
    <main class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Entre com seu email e senha</p>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    {{-- Protege o envio do formulário contra requisições CSRF. --}}
                    @csrf

                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        {{-- Mantém o email após um erro, sem preencher novamente a senha. --}}
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email"
                            autocomplete="email"
                            required
                            autofocus
                        />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="password">Senha</label>
                    <div class="input-group mb-3">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Senha"
                            autocomplete="current-password"
                            required
                        />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    Ainda não tem uma conta? <a href="{{ route('register') }}">Cadastre-se</a>
                </p>
            </div>
        </div>
    </main>
</body>
</html>
