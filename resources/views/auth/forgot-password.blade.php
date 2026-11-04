<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Recuperar senha</title>

    @vite('resources/scss/app.scss')
</head>

<body class="login-page bg-body-secondary">

    <main class="login-box">

        <div class="card">
            <div class="card-body login-card-body">

                <h4 class="text-center mb-3">
                    Recuperar senha
                </h4>

                <p class="login-box-msg">
                    Informe seu email para receber um
                    link de recuperação de senha.
                </p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form
                    action="{{ route('password.email') }}"
                    method="POST"
                >
                    @csrf

                    <div class="input-group mb-3">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            autofocus
                        >

                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Enviar link de recuperação
                        </button>
                    </div>

                </form>

                <p class="mt-3 mb-0 text-center">
                    <a href="{{ route('login') }}">
                        Voltar para o login
                    </a>
                </p>

            </div>
        </div>

    </main>

</body>
</html>
