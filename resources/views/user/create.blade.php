<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro dos Caçadores | Arquivos de Yharnam</title>
    @vite('resources/scss/app.scss')
</head>
<body class="register-page bg-dark text-light">
    <main class="register-box">
        <div class="card bg-dark border-danger">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Registro dos Caçadores - entre no Sonho do Caçador</p>

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
                        <button type="submit" class="btn btn-danger">Cadastrar</button>
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
