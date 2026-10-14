<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Recuperar senha</title>
    {{-- Copie aqui os <link> de CSS que a view de criar usuário já usa (AdminLTE/Bootstrap) --}}
</head>
<body class="login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h1 class="mb-0"><b>Recuperar</b> senha</h1>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Informe seu e-mail e a nova senha.</p>

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
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('user.alterar-senha') }}" method="POST">
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

                <button type="submit" class="btn btn-primary w-100">Alterar senha</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>