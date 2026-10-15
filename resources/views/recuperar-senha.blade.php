<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center"
         style="min-height: 100vh;">

        <div class="card shadow-sm p-4" style="width: 420px;">

            <h3 class="text-center mb-3">Recuperar senha</h3>

            <p class="text-muted text-center">
                Informe seu e-mail e cadastre uma nova senha.
            </p>

            @if (session('sucesso'))
                <div class="alert alert-success">
                    {{ session('sucesso') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $erro)
                        <p class="mb-1">{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('senha.alterar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" id="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="Digite seu e-mail" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nova senha</label>
                    <input type="password" name="password" id="password"
                           class="form-control"
                           placeholder="Digite a nova senha" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        Confirmar nova senha
                    </label>
                    <input type="password" name="password_confirmation"
                           id="password_confirmation" class="form-control"
                           placeholder="Repita a nova senha" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Alterar senha
                </button>
            </form>

        </div>
    </div>

</body>
</html>
```