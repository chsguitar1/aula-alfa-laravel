<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro</title>
    @vite('resources/scss/app.scss')
</head>
<body class="register-page bg-body-secondary">
    <main class="register-box">
        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Preencha os campos para se cadastrar</p>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <label class="visually-hidden" for="name">Nome</label>
                    <div class="input-group mb-3">
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nome"
                            autocomplete="name"
                            required
                        />
                        <div class="input-group-text">
                            <span class="bi bi-person"></span>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email"
                            autocomplete="email"
                            required
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
                            autocomplete="new-password"
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
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    Já tem uma conta? <a href="{{ route('login') }}">Entrar</a>
                </p>
            </div>
        </div>
    </main>
</body>
</html>
