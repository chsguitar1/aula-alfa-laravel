<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar | {{ config('app.name') }}</title>
    @vite('resources/scss/app.scss')
</head>
<body class="bg-body-tertiary">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <section class="card border-0 shadow-sm w-100" style="max-width: 28rem;" aria-labelledby="login-title">
            <div class="card-body p-4 p-md-5">
                <h1 id="login-title" class="h3 text-center mb-2">Entrar</h1>
                <p class="text-body-secondary text-center mb-4">Acesse sua conta para continuar.</p>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    @if ($errors->has('email') && ! old('email'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            placeholder="voce@exemplo.com"
                            autocomplete="email"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                            placeholder="Digite sua senha"
                            autocomplete="current-password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                    </div>
                </form>

                <p class="text-center mt-4 mb-2">
                    <a href="{{ route('password.request') }}" class="link-primary text-decoration-none">Esqueceu sua senha?</a>
                </p>

                <p class="text-center mb-0">
                    Ainda não tem uma conta?
                    <a href="{{ route('register') }}" class="link-primary text-decoration-none">Cadastre-se</a>
                </p>
            </div>
        </section>
    </main>
</body>
</html>
