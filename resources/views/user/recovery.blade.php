
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de senha</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h3 class="text-center mb-3">Recuperação de senha</h3>

            <p class="text-muted text-center">
                Informe seu e-mail e cadastre uma nova senha.
            </p>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.change') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" id="email"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nova senha</label>
                    <input type="password" name="password" id="password"
                           class="form-control" minlength="8" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        Confirmar nova senha
                    </label>
                    <input type="password" name="password_confirmation"
                           id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Alterar senha
                </button>

            </form>
        </div>
    </div>
</div>

</body>
</html>
