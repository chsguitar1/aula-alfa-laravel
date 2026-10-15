
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar senha</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    >

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
        }

        .login-box {
            width: 400px;
            max-width: 95%;
        }

        .login-logo {
            text-align: center;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .login-logo a {
            color: #343a40;
            text-decoration: none;
        }
    </style>
</head>
<body>

<main class="login-box">
    <h1 class="login-logo">
        <a href="#"><b></b></a>
    </h1>

    <div class="card">
        <div class="card-body">
            <p class="text-secondary">
                Informe seu e-mail e cadastre uma nova senha.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('senha.alterar') }}" method="POST">
                @csrf

                <div class="input-group mb-3">
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="E-mail"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="input-group mb-3">
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Nova senha"
                        required
                    >
                </div>

                <div class="input-group mb-3">
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirme a nova senha"
                        required
                    >
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Alterar senha
                    </button>
                </div>
            </form>

            <p class="mt-3 mb-0">
                <a href="{{ url('/') }}">Voltar</a>
            </p>
        </div>
    </div>
</main>

</body>
</html>

