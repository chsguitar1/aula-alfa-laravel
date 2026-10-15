<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar senha</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/css/adminlte.min.css">
</head>
<body class="login-page bg-body-secondary">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h1 class="mb-0">Recuperar senha</h1>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Informe seu e-mail e a nova senha.</p>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update-password') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control"
                           placeholder="E-mail" value="{{ old('email') }}" required>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control"
                           placeholder="Nova senha" required>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Confirmar nova senha" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Alterar senha</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>