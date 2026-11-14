<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/scss/app.scss')
</head>
<body class="login-page bg-body-secondary">
@vite('resources/js/app.js')
<main class="login-box">
    <h1 class="login-logo"><b>Login</b></h1>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Entre com seu e-mail e senha</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger py-1">{{ $error }}</div>
                @endforeach
            @endif

            <form action="{{ url('/login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" />
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="input-group mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Senha" />
                    <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>

            <p class="mt-3 mb-0"><a href="{{ url('register') }}">Criar uma conta</a></p>
        </div>
    </div>
</main>
</body>
</html>
