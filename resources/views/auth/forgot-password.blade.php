<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar senha | {{ config('app.name') }}</title>
    @vite('resources/scss/app.scss')
</head>
<body class="bg-body-tertiary">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <section class="card border-0 shadow-sm w-100" style="max-width: 28rem;" aria-labelledby="forgot-password-title">
            <div class="card-body p-4 p-md-5">
                <h1 id="forgot-password-title" class="h3 text-center mb-2">Recuperar senha</h1>
                <p class="text-body-secondary text-center mb-4">
                    Informe seu e-mail para receber as instruções de redefinição de senha.
                </p>

                <div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            autocomplete="email"
                            placeholder="voce@exemplo.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-lg">Enviar link de recuperação</button>
                    </div>
                </div>

                <p class="text-center mt-4 mb-0">
                    <a href="{{ route('login') }}" class="link-primary text-decoration-none">Voltar para o login</a>
                </p>
            </div>
        </section>
    </main>
</body>
</html>
