<!doctype html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AdminLTE 4 | Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css" />
</head>
<body class="login-page bg-body-secondary">
    <main class="login-box">
        <h1 class="login-logo">
            <a href="#"><b>Admin</b>LTE</a>
        </h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    Esqueceu sua senha? Informe seu e-mail e a nova senha.
                </p>

                @if($errors->any())
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('users.change-password') }}" method="post">
                    @csrf
                    <label class="visually-hidden" for="forgotEmail">Email</label>
                    <div class="input-group mb-3">
                        <input id="forgotEmail" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="newPassword">Nova senha</label>
                    <div class="input-group mb-3">
                        <input id="newPassword" type="password" name="password" class="form-control" placeholder="Nova senha" />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Alterar senha</button>
                            </div>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-0">
                    <a href="{{ url('register') }}" class="text-center"> Register a new membership </a>
                </p>
            </div>
        </div>
    </main>
</body>
</html>