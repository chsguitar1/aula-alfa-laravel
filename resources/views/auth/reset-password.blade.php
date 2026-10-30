<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redefinir senha</title>

    @vite('resources/scss/app.scss')
</head>

<body class="login-page bg-body-secondary">
    <main class="login-box">
        <div class="card">
            <div class="card-body login-card-body">

                <h4 class="text-center mb-3">
                    Redefinir senha
                </h4>

                <p class="login-box-msg">
                    Informe seu email e escolha uma nova senha.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        Verifique os dados informados.
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova senha</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            Confirmar nova senha
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="form-control"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Alterar senha
                        </button>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    <a href="{{ route('login') }}">Voltar para o login</a>
                </p>

            </div>
        </div>
    </main>
</body>
</html>
